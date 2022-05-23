<?php

declare(strict_types=1);

namespace DatName\Game;

use DatName\Game\Rom\Status;
use DatName\Hashes;
use Stringable;

final class Rom implements Stringable
{
    public function __construct(
        private string $name,
        private int $size,
        private Hashes $hashes,
        private Status $status = Status::GOOD,
    ) {
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getHashes(): Hashes
    {
        return $this->hashes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function isVerified(): bool
    {
        return Status::VERIFIED == $this->status;
    }
}
