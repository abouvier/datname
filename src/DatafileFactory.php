<?php

declare(strict_types=1);

namespace DatName;

use DatName\Datafile\Clrmamepro;
use DatName\Datafile\Directory;
use DatName\Datafile\Dummy;
use DatName\Datafile\Logiqx;

class DatafileFactory
{
    public static function create(Path $datafile): DatafileInterface
    {
        foreach ([
            Directory::class,
            Logiqx::class,
            Clrmamepro::class,
        ] as $class) {
            if ($class::validate($datafile)) {
                return new $class($datafile);
            }
        }

        return new Dummy();
    }
}
