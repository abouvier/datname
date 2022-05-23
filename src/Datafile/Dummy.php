<?php

declare(strict_types=1);

namespace DatName\Datafile;

use DatName\Interface\Datafile;
use DatName\Path;
use EmptyIterator;

final class Dummy implements Datafile
{
    public static function validate(Path $datafile): bool
    {
        return true;
    }

    public function getIterator(): EmptyIterator
    {
        return new EmptyIterator();
    }
}
