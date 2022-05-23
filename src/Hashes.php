<?php

declare(strict_types=1);

namespace DatName;

use Ramsey\Collection\AbstractSet;

final class Hashes extends AbstractSet
{
    public function getType(): string
    {
        return Hash::class;
    }

    public function matches(self $that): bool
    {
        foreach ($this as $hash) {
            if (!$that->contains($hash, false)) {
                return false;
            }
        }

        return true;
    }
}
