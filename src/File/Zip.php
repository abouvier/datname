<?php

declare(strict_types=1);

namespace Logiqx\File;

use Exception;
use Logiqx\File;
use Logiqx\Rom;
use SplFileInfo;
use ZipArchive;

class Zip extends File
{
    protected $stat;

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
        $zip = new ZipArchive();
        if (true !== $zip->open($file->getPathname())) {
            throw new Exception('zip open error');
        }
        if (1 != count($zip)) {
            throw new Exception('too many files in archive');
        }
        $this->stat = $zip->statIndex(0);
        $zip->close();
    }

    public function size(): int
    {
        return $this->stat['size'];
    }

    public function crc(): string
    {
        return sprintf('%08X', $this->stat['crc']);
    }

    public function md5(): string
    {
        return strtoupper(md5_file('zip://'.$this->file.'#'.$this->stat['name']));
    }

    public function sha1(): string
    {
        return strtoupper(sha1_file('zip://'.$this->file.'#'.$this->stat['name']));
    }

    public function rename(Rom $rom): void
    {
        $zip = new ZipArchive();
        $zip->open($this->file->getPathname());
        $zip->renameIndex(0, $rom->name);
        $zip->close();
        rename($this->file->getPathname(), $this->file->getPath().DIRECTORY_SEPARATOR.pathinfo($rom->name, PATHINFO_FILENAME).'.zip');
    }
}
