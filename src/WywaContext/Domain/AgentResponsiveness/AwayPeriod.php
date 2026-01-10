<?php

namespace App\WywaContext\Domain\AgentResponsiveness;

final class AwayPeriod
{
    public function __construct(
        private ?\DateTimeImmutable $startedAt,
        private ?\DateTimeImmutable $endedAt
    ) {}

    public function isActiveAt(\DateTimeImmutable $at): bool
    {
        return $this->startedAt !== null
            && $this->startedAt <= $at
            && (
                $this->endedAt === null
                || $this->endedAt < $this->startedAt
            );
    }

    public function hasEndedBy(\DateTimeImmutable $at): bool
    {
        return $this->endedAt !== null && $this->endedAt <= $at;
    }

    public function endedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }
}

