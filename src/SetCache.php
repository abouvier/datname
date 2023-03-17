<?php

declare(strict_types=1);

namespace DatName;

use DatName\Iterator\CachingGenerator;

final class SetCache extends Set
{
    /**
     * @var CachingGenerator<int, File>
     */
    private ?CachingGenerator $files = null;

    public function getIterator(): \Generator
    {
        if (is_null($this->files)) {
            $this->files = new CachingGenerator(parent::getIterator());
        }
        yield from $this->files;
    }

    public function rename(Game $game): void
    {
        parent::rename($game);
        unset($this->files);
    }
}
