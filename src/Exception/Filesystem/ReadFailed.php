<?php

declare(strict_types=1);

namespace DatName\Exception\Filesystem;

use DatName\Exception\Filesystem;

class ReadFailed extends Filesystem
{
    public function __construct(string|\Stringable $file)
    {
        parent::__construct(sprintf("Error while reading file '%s'.", (string) $file));
    }
}
