<?php

declare(strict_types=1);

namespace DatName;

use DatName\Exception\Filesystem;
use DatName\Exception\Filesystem\FileAlreadyExists;
use DatName\Interface\Set as SetInterface;
use DatName\Set\Directory;
use DatName\Set\Zip;
use Generator;
use IteratorAggregate;
use Stringable;

/**
 * @implements IteratorAggregate<int, File>
 */
class Set implements IteratorAggregate, Stringable
{
    public function __construct(private SetInterface $set, private Algos $algos)
    {
    }

    public function __toString(): string
    {
        return (string) $this->set;
    }

    /**
     * @return Generator<int, File>
     */
    public function getIterator(): Generator
    {
        foreach ($this->set as $file) {
            yield new FileCache($file, $this->algos);
        }
    }

    public function isDirectory(): bool
    {
        return $this->set instanceof Directory;
    }

    public function isZip(): bool
    {
        return $this->set instanceof Zip;
    }

    public function namedAfter(Game $game): bool
    {
        return $this->set->getPathname() == $this->set->getDatname($game);
    }

    public function rename(Game $game): void
    {
        $newname = $this->set->getDatname($game);
        if ($this->set->exists($newname)) {
            throw new FileAlreadyExists(sprintf("cannot rename set '%s' to '%s' because the target already exists", $this->set, $newname));
        }
        if (!$this->set->rename($newname)) {
            throw new Filesystem(sprintf("cannot rename set '%s' to '%s'", $this->set, $newname));
        }
    }

    public function similarityWith(Game $game): float
    {
        $nb_files = 0;
        $inter_size = 0;
        foreach ($this as $file) {
            foreach ($game as $rom) {
                if ($file->matches($rom)) {
                    ++$inter_size;
                    break;
                }
            }
            ++$nb_files;
        }

        return $inter_size / ($nb_files + count($game) - $inter_size);
    }
}
