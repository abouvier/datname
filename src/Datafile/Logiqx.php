<?php

declare(strict_types=1);

namespace DatName\Datafile;

use DatName\Exception\Filesystem\OpenFailed;
use DatName\Game;
use DatName\Game\Rom;
use DatName\Game\Rom\Status;
use DatName\Hash;
use DatName\Hash\Algo;
use DatName\Hashes;
use DatName\Interface\Datafile;
use DatName\Path;

final class Logiqx implements Datafile
{
    public static function validate(Path $datafile): bool
    {
        if ($datafile->isDir()) {
            return false;
        }
        foreach (['dom', 'libxml', 'SimpleXML'] as $ext) {
            if (!extension_loaded($ext)) {
                return false;
            }
        }
        libxml_set_external_entity_loader(
            function (?string $public_id, string $system_id): string {
                if ('http://www.logiqx.com/Dats/datafile.dtd' == $system_id) {
                    return __DIR__.'/../../assets/datafile.dtd';
                }

                return $system_id;
            }
        );
        $use_errors = libxml_use_internal_errors(true);
        $xml = new \DOMDocument();
        $xml->load((string) $datafile);
        try {
            return $xml->validate();
        } finally {
            libxml_use_internal_errors($use_errors);
            libxml_set_external_entity_loader(null);
        }
    }

    public function __construct(private Path $datafile)
    {
    }

    public function getIterator(): \Generator
    {
        $use_errors = libxml_use_internal_errors(true);
        $xml = simplexml_load_file((string) $this->datafile);
        libxml_use_internal_errors($use_errors);
        if (false === $xml) {
            throw new OpenFailed($this->datafile);
        }
        /** @var \SimpleXMLElement $game */
        foreach ($xml->game as $game) {
            $roms = [];
            /** @var \SimpleXMLElement $rom */
            foreach ($game->rom as $rom) {
                $hashes = new Hashes();
                foreach ([
                    'crc' => Algo::CRC,
                    'md5' => Algo::MD5,
                    'sha1' => Algo::SHA1,
                    'sha256' => Algo::SHA256,
                ] as $attr => $algo) {
                    if (isset($rom[$attr])) {
                        $hashes->add(
                            new Hash($algo, strtolower((string) $rom[$attr]))
                        );
                    }
                }
                $roms[] = new Rom(
                    (string) $rom['name'],
                    (int) $rom['size'],
                    $hashes,
                    Status::tryFrom((string) $rom['status']) ?? Status::GOOD,
                );
            }
            yield new Game(
                (string) $game['name'],
                (string) $game->description,
                $roms,
            );
        }
    }
}
