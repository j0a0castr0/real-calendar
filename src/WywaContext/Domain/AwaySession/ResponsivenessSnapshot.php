<?php

namespace App\WywaContext\Domain\AwaySession;

final class ResponsivenessSnapshot
{
    public function __construct(
        private readonly ResponsivenessConfidence $confidence,
        private readonly \DateTimeImmutable       $at
    ) {}

    public function confidence(): ResponsivenessConfidence
    {
        return $this->confidence;
    }

    public function at(): \DateTimeImmutable
    {
        return $this->at;
    }
}
