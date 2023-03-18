<?php

declare(strict_types=1);

namespace DatName\Datafile;

use DatName\DatafileFactory;
use DatName\DatafileInterface;
use DatName\Iterator\RecursiveExtensionFilterIterator;
use DatName\Path;

final class Directory implements DatafileInterface
{
    public static function validate(Path $datafile): bool
    {
        return $datafile->isDir();
    }

    public function __construct(private Path $datafile)
    {
    }

    public function getIterator(): \Generator
    {
        /** @var \RecursiveIterator<string, \SplFileInfo> */
        $dir = new \RecursiveDirectoryIterator((string) $this->datafile);
        $filter = new RecursiveExtensionFilterIterator($dir, 'dat');
        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator($filter) as $file) {
            foreach (DatafileFactory::create(Path::createFromSplFileInfo($file)) as $game) {
                yield $game;
            }
        }
    }
}
