<?php

declare(strict_types=1);

namespace DatName\Set;

use DatName\Factory\File as FileFactory;
use DatName\Path;
use FilesystemIterator;
use Generator;

class Directory extends File
{
    public static function validate(Path $set): bool
    {
        return $set->isDir();
    }

    public function getIterator(): Generator
    {
        foreach (new FilesystemIterator($this->getPathname()) as $file) {
            if ($file->isDir()) {
                continue;
            }
            yield FileFactory::create(Path::createFromSplFileInfo($file));
        }
    }
}
