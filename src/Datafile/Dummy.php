<?php

declare(strict_types=1);

namespace DatName\Datafile;

use DatName\DatafileInterface;
use DatName\Path;

final class Dummy implements DatafileInterface
{
    public static function validate(Path $datafile): bool
    {
        return true;
    }

    public function getIterator(): \EmptyIterator
    {
        return new \EmptyIterator();
    }
}
