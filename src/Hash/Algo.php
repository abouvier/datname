<?php

declare(strict_types=1);

namespace DatName\Hash;

enum Algo: string
{
    case CRC = 'crc32b';
    case MD5 = 'md5';
    case SHA1 = 'sha1';
    case SHA256 = 'sha256';
}
