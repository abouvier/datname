<?php

declare(strict_types=1);

namespace DatName\Interface;

use DatName\Game;
use DatName\Path;

/**
 * @extends \IteratorAggregate<int, Game>
 */
interface Datafile extends \IteratorAggregate
{
    public static function validate(Path $datafile): bool;
}
