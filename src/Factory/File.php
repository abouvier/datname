<?php

declare(strict_types=1);

namespace DatName\Factory;

use DatName\File\Generic;
use DatName\File\Nes;
use DatName\File\Zip;
use DatName\File\Zip\Nes as NesZip;
use DatName\Interface\File as FileInterface;
use DatName\Path;

class File
{
    public static function create(Path $file): FileInterface
    {
        foreach ([
            NesZip::class,
            Zip::class,
            Nes::class,
        ] as $class) {
            if ($class::validate($file)) {
                return new $class($file);
            }
        }

        return new Generic($file);
    }
}
