<?php

declare(strict_types=1);

namespace DatName;

final class FileCache extends File
{
    private ?Hashes $hashes = null;

    public function getHashes(): Hashes
    {
        if (is_null($this->hashes)) {
            $this->hashes = parent::getHashes();
        }

        return $this->hashes;
    }
}
