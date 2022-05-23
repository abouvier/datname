<?php

declare(strict_types=1);

namespace DatName\File;

use DatName\Exception\Filesystem\AccessDenied;
use DatName\Game\Rom;
use DatName\Hash\Algo;
use DatName\Interface\File;
use DatName\Path;
use DatName\Stream;

class Generic implements File
{
    public const READ_SIZE = 2 ** 16;

    public static function validate(Path $file): bool
    {
        return !$file->isDir();
    }

    public function __construct(protected Path $file)
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

    public function getCrc(): string
    {
        $crc = hash_init(Algo::CRC->value);
        hash_update_stream($crc, $this->getStream()->getInnerStream());

        return hash_final($crc);
    }

    public function getDatname(Rom $rom): string
    {
        return $this->file->getPath().DIRECTORY_SEPARATOR.$rom->getName();
    }

    public function getFilename(): string
    {
        return $this->file->getFilename();
    }

    public function getPathname(): string
    {
        return $this->file->getPathname();
    }

    public function getSize(): int
    {
        return $this->file->getSize();
    }

    public function getStream(): Stream
    {
        $stream = fopen($this->getPathname(), 'rb');
        if (false === $stream) {
            throw new AccessDenied(sprintf("cannot open file '%s'", $this->file));
        }

        return new Stream($stream);
    }

    public function rename(string $newname): bool
    {
        if (!rename($this->getPathname(), $newname)) {
            return false;
        }
        $this->file = new Path($newname);

        return true;
    }
}
