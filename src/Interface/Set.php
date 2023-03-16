<?php

declare(strict_types=1);

namespace DatName\Interface;

use DatName\Game;
use DatName\Path;
use IteratorAggregate;
use Stringable;

/**
 * @extends IteratorAggregate<int, File>
 */
interface Set extends IteratorAggregate, Stringable
{
    public static function validate(Path $set): bool;

    public function exists(string $newname): bool;

    public function getDatname(Game $game): string;

    public function getPathname(): string;

    public function rename(string $newname): bool;
}
