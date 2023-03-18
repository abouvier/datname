<?php

declare(strict_types=1);

namespace DatName\Exception\Filesystem;

use DatName\Exception\FilesystemException;

class FileAlreadyExistsException extends FilesystemException
{
    public function __construct(string|\Stringable $oldname, string $newname)
    {
        parent::__construct(sprintf("Cannot rename file '%s' to '%s' because the target already exists.", (string) $oldname, $newname));
    }
}
