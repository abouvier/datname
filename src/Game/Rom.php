<?php

declare(strict_types=1);

namespace DatName\Game;

use DatName\Game\Rom\Status;
use DatName\Hash;

final class Rom
{
    public function __construct(
        private string $name,
        private int $size,
        private Hash $hash,
        private Status $status = Status::GOOD,
    ) {
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getHash(): Hash
    {
        return $this->hash;
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
