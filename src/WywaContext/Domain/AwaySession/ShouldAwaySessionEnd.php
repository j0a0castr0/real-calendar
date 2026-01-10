<?php

namespace App\WywaContext\Domain\AwaySession;

use App\WywaContext\Domain\AgentResponsiveness\ResponsivenessSnapshot;

final class ShouldAwaySessionEnd
{
    public function decide(ResponsivenessSnapshot $snapshot): bool
    {
        return $snapshot->isConfidenceMediumOrHigh();
    }
}
