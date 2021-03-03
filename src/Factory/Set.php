<?php

declare(strict_types=1);

namespace DatName\Factory;

use DatName\Interface\Set as SetInterface;
use DatName\Path;
use DatName\Set\Directory;
use DatName\Set\File;
use DatName\Set\Zip;

class Set
{
    public static function create(Path $set): SetInterface
    {
        foreach ([Directory::class, Zip::class, File::class] as $class) {
            if ($class::validate($set)) {
                return new $class($set);
            }
        }
    }
}
