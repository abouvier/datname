<?php

declare(strict_types=1);

namespace DatName\Set;

use DatName\Exception\Filesystem\OpenFailedException;
use DatName\FileFactory;
use DatName\Game;
use DatName\Path;

class Zip extends Directory
{
    public static function validate(Path $set): bool
    {
        if (!extension_loaded('zip')) {
            return false;
        }

        return true === (new \ZipArchive())->open((string) $set);
    }

    public function getDatname(Game $game): string
    {
        return parent::getDatname($game).'.zip';
    }

    public function getIterator(): \Generator
    {
        $zip = new \ZipArchive();
        if (true !== $zip->open((string) $this->set)) {
            throw new OpenFailedException($this->set);
        }
        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $entryname = $zip->getNameIndex($i);
            if (str_contains($entryname, DIRECTORY_SEPARATOR)) {
                continue;
            }
            yield FileFactory::create($this->set->withEntryname($entryname));
        }
    }
}
