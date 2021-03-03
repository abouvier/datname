<?php

declare(strict_types=1);

namespace DatName\Interface;

use DatName\Path;
use IteratorAggregate;

interface Datafile extends IteratorAggregate
{
    public static function validate(Path $datafile): bool;
}
