<?php

declare(strict_types=1);

namespace Logiqx;

use SplFileInfo;

abstract class File
{
    protected $file;

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }

    abstract public function size(): int;

    abstract public function crc(): string;

    abstract public function md5(): string;

    abstract public function sha1(): string;

    abstract public function rename(Rom $rom): void;
}
