<?php

declare(strict_types=1);

namespace Logiqx\File;

use Logiqx\File;
use RuntimeException;

class Nes extends File
{
    public function __construct(string $filename)
    {
        parent::__construct($filename);
        $header = fread($this->stream, 16);
        if ("NES\x1A" != substr($header, 0, 4)) {
            throw new RuntimeException('invalid header');
        }
    }

    public function getSize(): int
    {
        return parent::getSize() - 16;
    }
}
