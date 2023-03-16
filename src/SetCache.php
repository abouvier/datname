<?php

declare(strict_types=1);

namespace DatName;

use CachingIterator;
use Generator;
use IteratorAggregate;

final class SetCache extends Set
{
    /**
     * @var IteratorAggregate<int, File>
     */
    private ?IteratorAggregate $files = null;

    public function getIterator(): Generator
    {
        if (is_null($this->files)) {
            $this->files = new /**
              * @implements IteratorAggregate<int, File>
              */ class(parent::getIterator()) implements IteratorAggregate {
                private CachingIterator $iterator;

                public function __construct(Generator $generator)
                {
                    $this->iterator = new CachingIterator($generator, CachingIterator::FULL_CACHE);
                }

                public function getIterator(): Generator
                {
                    if ($this->iterator->hasNext()) {
                        yield from $this->iterator;
                    } else {
                        yield from $this->iterator->getCache();
                    }
                }
            };
        }
        yield from $this->files;
    }

    public function rename(Game $game): void
    {
        parent::rename($game);
        unset($this->files);
    }
}
