<?php

namespace App\WywaContext\Domain\AgentResponsiveness;

enum Confidence : string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}
