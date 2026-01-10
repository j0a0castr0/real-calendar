<?php

namespace App\WywaContext\Domain\AwaySession;

enum ResponsivenessConfidence: string
{
    case HIGH = 'high';
    case LOW = 'low';
    case MEDIUM = 'medium';
}
