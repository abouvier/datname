<?php

declare(strict_types=1);

namespace DatName\Factory;

use DatName\Datafile\Clrmamepro;
use DatName\Datafile\Directory;
use DatName\Datafile\Dummy;
use DatName\Datafile\Logiqx;
use DatName\Interface\Datafile as DatafileInterface;
use DatName\Path;

class Datafile
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
