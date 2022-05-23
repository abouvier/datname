<?php

declare(strict_types=1);

namespace DatName\Datafile;

use DatName\Game;
use DatName\Game\Rom;
use DatName\Hash;
use DatName\Hash\Algo;
use DatName\Hashes;
use DatName\Interface\Datafile;
use DatName\Parser\Clrmamepro as ClrmameproParser;
use DatName\Path;
use Generator;

final class Clrmamepro implements Datafile
{
    public static function validate(Path $datafile): bool
    {
        if ($datafile->isDir()) {
            return false;
        }
        if (!extension_loaded('parle')) {
            return false;
        }
        $parser = new ClrmameproParser();

        return $parser->validateFile(strval($datafile));
    }

    public function __construct(private Path $datafile)
    {
    }

    public function getIterator(): Generator
    {
        $parser = new ClrmameproParser();
        $sections = $parser->parseFile(strval($this->datafile));
        foreach ($sections['game'] as $game) {
            $roms = [];
            foreach ($game['rom'] as $rom) {
                $hashes = new Hashes();
                foreach ([
                    'crc' => Algo::CRC,
                    'md5' => Algo::MD5,
                    'sha1' => Algo::SHA1,
                    'sha256' => Algo::SHA256,
                ] as $attr => $algo) {
                    if (isset($rom[$attr])) {
                        $hashes->add(
                            new Hash($algo, strtolower(strval($rom[$attr])))
                        );
                    }
                }
                $roms[] = new Rom(
                    strval($rom['name']),
                    intval($rom['size']),
                    $hashes,
                );
            }
            yield new Game(
                strval($game['name']),
                strval($game['description']),
                $roms,
            );
        }
    }
}
