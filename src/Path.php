<?php

declare(strict_types=1);

namespace DatName;

use SplFileInfo;

final class Path extends SplFileInfo
{
    public static function createFromSplFileInfo(SplFileInfo $path): static
    {
        return new static(strval($path));
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

    public function withEntryname(string $entryname): static
    {
        $that = clone $this;
        $that->entryname = $entryname;

        return $that;
    }
}
