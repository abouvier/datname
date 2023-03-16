<?php

declare(strict_types=1);

namespace DatName\Set;

use DatName\Exception\Filesystem\AccessDenied;
use DatName\Factory\File;
use DatName\Game;
use DatName\Path;
use Generator;
use ZipArchive;

class Zip extends Directory
{
    public static function validate(Path $set): bool
    {
        if (!extension_loaded('zip')) {
            return false;
        }

        return true === (new ZipArchive())->open((string) $set);
    }

    public function getDatname(Game $game): string
    {
        return parent::getDatname($game).'.zip';
    }

    public function getIterator(): Generator
    {
        $zip = new ZipArchive();
        if (true !== $zip->open((string) $this->set)) {
            throw new AccessDenied('zip open error');
        }
        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $entryname = $zip->getNameIndex($i);
            if (str_contains($entryname, DIRECTORY_SEPARATOR)) {
                continue;
            }
            yield File::create($this->set->withEntryname($entryname));
        }
    }
}
