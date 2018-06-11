<?php

declare(strict_types=1);

namespace Logiqx\File;

use Logiqx\File;
use Logiqx\Rom;

class Regular extends File
{
    public function size(): int
    {
        return $this->file->getSize();
    }

    public function crc(): string
    {
        return strtoupper(hash_file('crc32b', $this->file->getPathname()));
    }

    public function md5(): string
    {
        return strtoupper(md5_file($this->file->getPathname()));
    }

    public function sha1(): string
    {
        return strtoupper(sha1_file($this->file->getPathname()));
    }

    public function rename(Rom $rom): void
    {
        rename($this->file->getPathname(),
            $this->file->getPath().DIRECTORY_SEPARATOR.$rom->name
        );
    }
}
