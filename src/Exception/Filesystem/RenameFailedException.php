<?php

declare(strict_types=1);

namespace DatName\Exception\Filesystem;

use DatName\Exception\FilesystemException;

class RenameFailedException extends FilesystemException
{
    public function __construct(string|\Stringable $oldname, string $newname)
    {
        parent::__construct(sprintf("Cannot rename file '%s' to '%s'.", (string) $oldname, $newname));
    }
}
