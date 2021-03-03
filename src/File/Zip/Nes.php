<?php

declare(strict_types=1);

namespace DatName\File\Zip;

use DatName\File\Generic;
use DatName\File\Zip;
use DatName\Path;
use DatName\Stream;
use ZipArchive;

final class Nes extends Zip
{
    public static function validate(Path $file): bool
    {
        if (!extension_loaded('zip')) {
            return false;
        }
        $zip = new ZipArchive();
        if (true !== $zip->open($file->getPathname())) {
            return false;
        }
        if (!($stream = $zip->getStream($file->getEntryname()))) {
            return false;
        }
        $header = fread($stream, 16);

        return 16 == strlen($header) and str_starts_with($header, "NES\x1A");
    }

    public function getCrc(): string
    {
        return Generic::getCrc();
    }

    public function getSize(): int
    {
        return parent::getSize() - 16;
    }

    public function getStream(): Stream
    {
        $stream = parent::getStream();
        $stream->seek(16);

        return $stream;
    }
}
