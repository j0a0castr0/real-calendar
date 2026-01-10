<?php

namespace App\WywaContext\Domain\AwaySession;

final class ShouldAwaySessionEnd
{
    public function decide(ResponsivenessSnapshot $snapshot): bool
    {
        return $snapshot->confidence() !== ResponsivenessConfidence::LOW;
    }
}
