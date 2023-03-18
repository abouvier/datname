<?php

declare(strict_types=1);

namespace DatName;

use DatName\Set\Directory;
use DatName\Set\File;
use DatName\Set\Zip;

class SetFactory
{
    public static function create(Path $set): SetInterface
    {
        foreach ([Directory::class, Zip::class] as $class) {
            if ($class::validate($set)) {
                return new $class($set);
            }
        }

        return new File($set);
    }
}
