<?php

declare(strict_types=1);

namespace DatName\Exception;

use DatName\Interface\Exception;
use RuntimeException;

class Stream extends RuntimeException implements Exception
{
}