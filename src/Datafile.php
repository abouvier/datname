<?php

declare(strict_types=1);

namespace DatName;

use DatName\Interface\Datafile as DatafileInterface;
use Generator;
use IteratorAggregate;

final class Datafile implements IteratorAggregate
{
    public function __construct(private DatafileInterface $datafile)
    {
    }

    public function getIterator(): Generator
    {
        yield from $this->datafile;
    }
}
