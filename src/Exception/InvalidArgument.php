<?php

declare(strict_types=1);

namespace DatName\Exception;

use DatName\Interface\Exception;

class InvalidArgument extends \InvalidArgumentException implements Exception
{
}
