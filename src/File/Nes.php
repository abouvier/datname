<?php

declare(strict_types=1);

namespace DatName\File;

use DatName\Path;
use DatName\Stream;

final class Nes extends Generic
{
    public static function validate(Path $file): bool
    {
        $header = $file->openFile('rb')->fread(16);

        return 16 == strlen($header) and str_starts_with($header, "NES\x1A");
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
