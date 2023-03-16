<?php

declare(strict_types=1);

namespace DatName;

use Countable;
use DatName\Game\Rom;
use Generator;
use IteratorAggregate;
use Stringable;

/**
 * @implements IteratorAggregate<int, Rom>
 */
final class Game implements Countable, IteratorAggregate, Stringable
{
    /**
     * @param list<Rom> $roms
     */
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

    /**
     * @return Generator<int, Rom>
     */
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
