<?php

declare(strict_types=1);

namespace DatName\Exception\Filesystem;

use DatName\Exception\Filesystem;

class FileNotFound extends Filesystem
{
    public function __construct(string|\Stringable $file)
    {
        parent::__construct(sprintf("The file '%s' does not exist.", (string) $file));
    }
}
