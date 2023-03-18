<?php

declare(strict_types=1);

namespace DatName;

/**
 * @extends \IteratorAggregate<int, FileInterface>
 */
interface SetInterface extends \IteratorAggregate, \Stringable
{
    public static function validate(Path $set): bool;

    public function exists(string $newname): bool;

    public function getDatname(Game $game): string;

    public function getPathname(): string;

    public function rename(string $newname): bool;
}
