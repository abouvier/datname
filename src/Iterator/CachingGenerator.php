<?php

declare(strict_types=1);

namespace DatName\Iterator;

/**
 * @template-covariant TKey
 * @template-covariant TValue
 *
 * @implements \IteratorAggregate<TKey, TValue>
 */
final class CachingGenerator implements \IteratorAggregate
{
    /**
     * @var \CachingIterator<TKey, TValue, \Generator<TKey, TValue>>
     */
    private \CachingIterator $iterator;

    /**
     * @param \Generator<TKey, TValue> $generator
     */
    public function __construct(\Generator $generator)
    {
        $this->iterator = new \CachingIterator($generator, \CachingIterator::FULL_CACHE);
    }

    /**
     * @return \Generator<TKey, TValue>
     */
    public function getIterator(): \Generator
    {
        if ($this->iterator->hasNext()) {
            yield from $this->iterator;
        } else {
            yield from $this->iterator->getCache();
        }
    }
}
