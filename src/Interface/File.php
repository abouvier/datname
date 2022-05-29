<?php

declare(strict_types=1);

namespace DatName\Interface;

use DatName\Game\Rom;
use DatName\Path;
use DatName\Stream;
use Stringable;

interface File extends Stringable
{
    public static function validate(Path $file): bool;

    public function exists(string $newname): bool;

    public function getDatname(Rom $rom): string;

    public function getFastCrc(): string;

    public function getFilename(): string;

    public function getPathname(): string;

    public function getSize(): int;

    public function getStream(): Stream;

    public function rename(string $newname): bool;
}
