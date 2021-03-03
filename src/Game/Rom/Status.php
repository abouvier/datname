<?php

declare(strict_types=1);

namespace DatName\Game\Rom;

abstract class Status
{
    public const BAD_DUMP = 'baddump';
    public const GOOD = 'good';
    public const NO_DUMP = 'nodump';
    public const VERIFIED = 'verified';
}
