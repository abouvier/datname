<?php

declare(strict_types=1);

namespace DatName;

/**
 * @extends \IteratorAggregate<int, Game>
 */
interface DatafileInterface extends \IteratorAggregate
{
    public static function validate(Path $datafile): bool;
}
