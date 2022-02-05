<?php

declare(strict_types=1);

namespace DatName\Datafile;

use DatName\Exception\Filesystem\AccessDenied;
use DatName\Game;
use DatName\Game\Rom;
use DatName\Game\Rom\Status;
use DatName\Hash;
use DatName\Interface\Datafile;
use DatName\Path;
use Generator;

final class Logiqx implements Datafile
{
    public static function validate(Path $datafile): bool
    {
        return !$datafile->isDir();
    }

    public function __construct(private Path $datafile)
    {
        libxml_use_internal_errors(true);
    }

    public function getIterator(): Generator
    {
        $xml = simplexml_load_file($this->datafile->getPathname());
        if (false === $xml) {
            throw new AccessDenied('xml load error');
        }
        foreach ($xml->game as $game) {
            $roms = [];
            foreach ($game->rom as $rom) {
                $hashes = [];
                foreach ([
                    Hash::CRC => 'crc',
                    Hash::SHA1 => 'sha1',
                    Hash::MD5 => 'md5',
                ] as $algo => $attr) {
                    if (isset($rom[$attr])) {
                        $hashes[$algo] = strtolower(strval($rom[$attr]));
                    }
                }
                $roms[] = new Rom(
                    strval($rom['name']),
                    intval($rom['size']),
                    new Hash($hashes),
                    Status::tryFrom(strval($rom['status'])) ?? Status::GOOD,
                );
            }
            yield new Game(
                strval($game['name']),
                strval($game->description),
                $roms,
            );
        }
    }
}
