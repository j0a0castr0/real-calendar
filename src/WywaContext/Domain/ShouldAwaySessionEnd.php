<?php

namespace App\WywaContext\Domain;

use App\Shared\Domain\AggregateId;

final class ShouldAwaySessionEnd
{
    public function decide(CommitmentSnapshot $presenceSnapshot): bool
    {
        return $presenceSnapshot->confidence() !== CommitmentConfidence::LOW;
    }
}
