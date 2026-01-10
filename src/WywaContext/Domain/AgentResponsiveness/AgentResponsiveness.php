<?php

namespace App\WywaContext\Domain\AgentResponsiveness;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\DomainEvent;
use App\WywaContext\Domain\Shared\DemandId;

final class AgentResponsiveness extends AggregateRoot
{
    /** @var array<string, bool> */
    private array $demandsAcknowledged = [];
    private ?\DateTimeImmutable $awayPeriodStartedAt = null;
    private ?\DateTimeImmutable $awayPeriodEndedAt = null;
    private ?\DateTimeImmutable $lastAcknowledgedAt = null;

    /**
     * @return DomainEvent[]
     */
    public function eventsForDemandAcknowledged(DemandId $demandId, \DateTimeImmutable $at): array
    {
        if (isset($this->demandsAcknowledged[(string) $demandId])) return [];
        return [new DemandAcknowledged($this->id, (string) $demandId, $at)];
    }

    public function applyDemandAcknowledged(DemandAcknowledged $event) : void
    {
        $this->demandsAcknowledged[(string) $event->demandId()] = true;
        $this->lastAcknowledgedAt = $event->occurredAt();
    }

    public function confidenceAt(\DateTimeImmutable $at) : Confidence
    {
        $away = new AwayPeriod($this->awayPeriodStartedAt, $this->awayPeriodEndedAt);

        if ($away->isActiveAt($at)) {
            return Confidence::LOW;
        }

        $hasAnyAcknowledgementAfter = function (\DateTimeImmutable $at, $lastAcknowledgedAt) : bool {
            return $at !== null
                && $lastAcknowledgedAt !== null
                && $lastAcknowledgedAt > $at;
        };

        if ($away->hasEndedBy($at)) {
            return $hasAnyAcknowledgementAfter($away->endedAt(), $this->lastAcknowledgedAt)
                ? Confidence::HIGH
                : Confidence::MEDIUM;
        }

        $hasAnyAcknowledgement = function (array $demandsAcknowledged) : bool {
            return count($demandsAcknowledged) > 0;
        };

        return $hasAnyAcknowledgement($this->demandsAcknowledged)
            ? Confidence::HIGH
            : Confidence::LOW;
    }

    public function eventsForStartAwayPeriod(\DateTimeImmutable $at)
    {
        return [new AwayPeriodStarted($this->id, $at)];
    }

    public function applyAwayPeriodStarted(AwayPeriodStarted $event) : void
    {
        $this->awayPeriodStartedAt = $event->occurredAt();
    }

    public function eventsForEndAwayPeriod(\DateTimeImmutable $at)
    {
        return [new AwayPeriodEnded($this->id, $at)];
    }

    public function applyAwayPeriodEnded(AwayPeriodEnded $event) : void
    {
        $this->awayPeriodEndedAt = $event->occurredAt();
    }
}
