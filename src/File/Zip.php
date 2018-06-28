<?php

declare(strict_types=1);

namespace Logiqx\File;

use Logiqx\File;
use Logiqx\Rom;
use RuntimeException;
use SplFileInfo;
use ZipArchive;

class Zip extends File
{
    protected $stat;

    public function __construct(string $filename)
    {
        $zip = new ZipArchive();
        if (true !== $zip->open($filename)) {
            throw new RuntimeException('zip open error');
        }
        if (1 != count($zip)) {
            throw new RuntimeException('single file archive required');
        }
        $this->stat = $zip->statIndex(0);
        $this->stream = $zip->getStream($this->stat['name']);
        SplFileInfo::__construct($filename);
    }

    public function getSize(): int
    {
        return $this->stat['size'];
    }

    public function crc(): string
    {
        return sprintf('%08x', $this->stat['crc']);
    }

    public function rename(Rom $rom): void
    {
        $zip = new ZipArchive();
        $zip->open($this->getPathname());
        $zip->renameName($this->stat['name'], $rom->name);
        $zip->close();
        $newpathname = $this->getPath().DIRECTORY_SEPARATOR.pathinfo(
            $rom->name,
            PATHINFO_FILENAME
        ).'.zip';
        if (file_exists($newpathname)) {
            throw new RuntimeException('file name already exists');
        }
        rename($this->getPathname(), $newpathname);
    }
}
