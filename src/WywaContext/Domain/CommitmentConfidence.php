<?php

namespace App\WywaContext\Domain;

enum CommitmentConfidence: string
{
    case HIGH = 'high';
    case LOW = 'low';
    case MEDIUM = 'medium';
}
