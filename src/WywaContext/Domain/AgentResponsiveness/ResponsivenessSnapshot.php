<?php

namespace App\WywaContext\Domain\AgentResponsiveness;

final class ResponsivenessSnapshot
{
    public function __construct(
        private readonly Confidence $confidence,
        private readonly \DateTimeImmutable       $at
    ) {}

    public function confidence(): Confidence
    {
        return $this->confidence;
    }

    public function at(): \DateTimeImmutable
    {
        return $this->at;
    }

    public function isConfidenceLow(): bool
    {
        return $this->confidence === Confidence::LOW;
    }

    public function isConfidenceMediumOrHigh(): bool
    {
        return
            $this->confidence === Confidence::MEDIUM
            || $this->confidence === Confidence::HIGH;
    }
}
