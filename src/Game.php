<?php

declare(strict_types=1);

namespace DatName;

use Countable;
use Generator;
use IteratorAggregate;

final class Game implements Countable, IteratorAggregate
{
    public function __construct(
        private string $name,
        private string $description,
        private array $roms,
    ) {
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function count(): int
    {
        return count($this->roms);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIterator(): Generator
    {
        foreach ($this->roms as $rom) {
            yield $rom;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
}
