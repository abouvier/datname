<?php

declare(strict_types=1);

namespace DatName\Set;

use DatName\FileFactory;
use DatName\Game;
use DatName\Path;
use DatName\SetInterface;

class File implements SetInterface
{
    public static function validate(Path $set): bool
    {
        return !$set->isDir();
    }

    public function __construct(protected Path $set)
    {
    }

    public function __toString(): string
    {
        return $this->getPathname();
    }

    public function exists(string $newname): bool
    {
        return file_exists($newname);
    }

    public function getDatname(Game $game): string
    {
        return $this->set->getPath().DIRECTORY_SEPARATOR.$game->getName();
    }

    public function getIterator(): \Generator
    {
        yield FileFactory::create($this->set);
    }

    public function getPathname(): string
    {
        return $this->set->getPathname();
    }

    public function rename(string $newname): bool
    {
        if (!rename($this->getPathname(), $newname)) {
            return false;
        }
        $this->set = new Path($newname);

        return true;
    }
}
