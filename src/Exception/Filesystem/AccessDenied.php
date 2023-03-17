<?php

declare(strict_types=1);

namespace DatName\Exception\Filesystem;

use DatName\Exception\Filesystem;

class AccessDenied extends Filesystem
{
    public function __construct(string|\Stringable $file)
    {
        parent::__construct(sprintf("The file '%s' is not readable.", (string) $file));
    }
}
