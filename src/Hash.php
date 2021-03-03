<?php

declare(strict_types=1);

namespace DatName;

final class Hash
{
    public const CRC = 'crc32b';
    public const MD5 = 'md5';
    public const SHA1 = 'sha1';

    public function __construct(private array $hashes = [])
    {
    }

    public function equals(self $that): bool
    {
        foreach (array_keys($this->hashes) as $algo) {
            if (isset($this->hashes[$algo], $that->hashes[$algo]) and $this->hashes[$algo] != $that->hashes[$algo]) {
                return false;
            }
        }

        return true;
    }
}
