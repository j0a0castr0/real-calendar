<?php

namespace App\WywaContext\Domain;

final class CommitmentSnapshot
{
    public function __construct(
        private readonly CommitmentConfidence $confidence,
        private readonly \DateTimeImmutable $at
    ) {}

    public function confidence(): CommitmentConfidence
    {
        return $this->confidence;
    }

    public function at(): \DateTimeImmutable
    {
        return $this->at;
    }
}
