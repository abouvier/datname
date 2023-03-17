<?php

declare(strict_types=1);

namespace DatName\Datafile;

use DatName\Factory\Datafile as DatafileFactory;
use DatName\Interface\Datafile;
use DatName\Iterator\ExtensionRecursiveFilter;
use DatName\Path;

final class Directory implements Datafile
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
        $filter = new ExtensionRecursiveFilter($dir, 'dat');
        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator($filter) as $file) {
            foreach (DatafileFactory::create(Path::createFromSplFileInfo($file)) as $game) {
                yield $game;
            }
        }
    }
}
