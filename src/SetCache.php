<?php

declare(strict_types=1);

namespace DatName;

use Lunkkun\CachingGenerator\CachingGenerator;

final class SetCache extends Set
{
    private ?CachingGenerator $files = null;

    public function getIterator(): CachingGenerator
    {
        if (is_null($this->files)) {
            $this->files = new CachingGenerator(parent::getIterator());
        }

        return $this->files;
    }

    public function rename(Game $game): void
    {
        parent::rename($game);
        unset($this->files);
    }
}
