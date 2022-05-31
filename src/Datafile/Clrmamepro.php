<?php

declare(strict_types=1);

namespace DatName\Datafile;

use Abouvier\Clrmamepro\Parser;
use DatName\Game;
use DatName\Game\Rom;
use DatName\Hash;
use DatName\Hash\Algo;
use DatName\Hashes;
use DatName\Interface\Datafile;
use DatName\Path;
use Exception;
use Generator;

final class Clrmamepro implements Datafile
{
    public static function validate(Path $datafile): bool
    {
        if ($datafile->isDir() or !$datafile->getSize()) {
            return false;
        }
        if (!extension_loaded('parle')) {
            return false;
        }
        $parser = new Parser();
        try {
            return $parser->validate($datafile->readFile());
        } catch (Exception) {
            return false;
        }
    }

    public function __construct(private Path $datafile)
    {
    }

    public function getIterator(): Generator
    {
        $parser = new Parser();
        $sections = $parser->parse($this->datafile->readFile());
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
