<?php

declare(strict_types=1);

namespace DatName\Game\Rom;

enum Status: string
{
    case BAD_DUMP = 'baddump';
    case GOOD = 'good';
    case NO_DUMP = 'nodump';
    case VERIFIED = 'verified';
}
