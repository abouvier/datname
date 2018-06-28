<?php

declare(strict_types=1);

namespace Logiqx;

use RuntimeException;
use SplFileInfo;

class File extends SplFileInfo
{
    protected $stream;

    public function __construct(string $filename)
    {
        $this->stream = fopen($filename, 'rb');
        if (!$this->stream) {
            throw new RuntimeException('cannot open file');
        }
        parent::__construct($filename);
    }

    protected function hash(string $algo): string
    {
        $hash = hash_init($algo);
        $size = hash_update_stream($hash, $this->stream);
        fseek($this->stream, -$size, SEEK_CUR);

        return hash_final($hash);
    }

    public function crc(): string
    {
        return $this->hash('crc32b');
    }

    public function md5(): string
    {
        return $this->hash('md5');
    }

    public function sha1(): string
    {
        return $this->hash('sha1');
    }

    public function rename(Rom $rom): void
    {
        $newpathname = $this->getPath().DIRECTORY_SEPARATOR.$rom->name;
        if (file_exists($newpathname)) {
            throw new RuntimeException('file name already exists');
        }
        rename($this->getPathname(), $newpathname);
    }
}
