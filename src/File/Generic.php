<?php

declare(strict_types=1);

namespace DatName\File;

use DatName\Exception\Filesystem;
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

    public function getDatname(Rom $rom): string
    {
        return $this->file->getPath().DIRECTORY_SEPARATOR.$rom->getName();
    }

    public function getFastCrc(): string
    {
        $crc = hash_init(Algo::CRC->value);
        hash_update_stream($crc, $this->getStream()->getInnerStream());

        return hash_final($crc);
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
        set_error_handler(function (int $severity, string $message): bool {
            throw new Filesystem($message);
        });
        try {
            return new Stream(fopen((string) $this->file, 'rb'));
        } finally {
            restore_error_handler();
        }
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
