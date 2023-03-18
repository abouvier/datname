<?php

declare(strict_types=1);

namespace DatName;

/**
 * @implements \IteratorAggregate<int, Game>
 */
final class Datafile implements \IteratorAggregate
{
    public function __construct(private DatafileInterface $datafile)
    {
    }

    /**
     * @return \Generator<int, Game>
     */
    public function getIterator(): \Generator
    {
        yield from $this->datafile;
    }
}
