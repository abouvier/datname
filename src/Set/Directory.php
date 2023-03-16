<?php

declare(strict_types=1);

namespace DatName\Set;

use DatName\Factory\File as FileFactory;
use DatName\Path;

class Directory extends File
{
    public static function validate(Path $set): bool
    {
        return $set->isDir();
    }

    public function getIterator(): \Generator
    {
        /**
         * @var \SplFileInfo $file
         */
        foreach (new \FilesystemIterator($this->getPathname()) as $file) {
            if ($file->isDir()) {
                continue;
            }
            yield FileFactory::create(Path::createFromSplFileInfo($file));
        }
    }
}
