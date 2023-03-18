<?php

declare(strict_types=1);

namespace DatName;

use DatName\Exception\FilesystemException;

final class Path extends \SplFileInfo
{
    public static function createFromSplFileInfo(\SplFileInfo $path): static
    {
        return new static((string) $path);
    }

    public function __construct(
        string $pathname,
        private string $entryname = '',
    ) {
        parent::__construct($pathname);
    }

    public function getEntryname(): string
    {
        return $this->entryname;
    }

    public function readFile(): string
    {
        set_error_handler(function (int $severity, string $message): bool {
            throw new FilesystemException($message);
        });
        try {
            return file_get_contents((string) $this);
        } finally {
            restore_error_handler();
        }
    }

    public function withEntryname(string $entryname): static
    {
        $that = clone $this;
        $that->entryname = $entryname;

        return $that;
    }
}
