<?php

declare(strict_types=1);

namespace DatName;

use DatName\Hash\Algo;

final class Hash implements \Stringable
{
    public function __construct(
        public readonly Algo $algo,
        public readonly string $hash,
    ) {
    }

    public function __toString(): string
    {
        return $this->hash;
    }
}
