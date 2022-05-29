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

    public function onlyCrc(): bool
    {
        return 1 == count($this) and $this->contains(Algo::CRC);
    }
}
