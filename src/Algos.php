<?php

declare(strict_types=1);

namespace DatName;

use DatName\Hash\Algo;
use Ramsey\Collection\AbstractSet;

final class Algos extends AbstractSet
{
    public function getType(): string
    {
        return Algo::class;
    }
}
