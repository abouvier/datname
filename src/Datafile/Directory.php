<?php

declare(strict_types=1);

namespace DatName\Datafile;

use DatName\Factory\Datafile as DatafileFactory;
use DatName\Interface\Datafile;
use DatName\Path;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveFilterIterator;
use RecursiveIteratorIterator;

final class Directory implements Datafile
{
    public static function validate(Path $datafile): bool
    {
        return $datafile->isDir();
    }

    public function __construct(private Path $datafile)
    {
    }

    public function getIterator(): Generator
    {
        $dir = new RecursiveDirectoryIterator($this->datafile->getPathname());
        $filter = new class($dir) extends RecursiveFilterIterator {
            public function accept(): bool
            {
                $ext = $this->current()->getExtension();

                return $this->hasChildren() or 0 == strcasecmp($ext, 'dat');
            }
        };
        foreach (new RecursiveIteratorIterator($filter) as $file) {
            foreach (DatafileFactory::create(Path::createFromSplFileInfo($file)) as $game) {
                yield $game;
            }
        }
    }
}
