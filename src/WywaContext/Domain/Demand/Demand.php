<?php

namespace App\WywaContext\Domain\Demand;

use App\Shared\Domain\AggregateId;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\DomainEvent;

final class Demand extends AggregateRoot
{
    private bool $isReceived = false;
    private bool $isAcknowledged = false;

    public static function receive(\DateTimeImmutable $at, ?AggregateId $id = null): self
    {
        return self::reconstitute($aggregateId = $id ?? new AggregateId(), [])
            ->withEvents([
                new DemandReceived($aggregateId, $at),
            ]);
    }

    public function applyDemandReceived(DemandReceived $event): void
    {
        $this->isReceived = true;
    }

    /**
     * @return DomainEvent[]
     */
    public function eventsForAcknowledgeDemand(\DateTimeImmutable $at) : array
    {
        if ($this->isReceived === false) return [];
        if ($this->isAcknowledged === true) return [];

        return [new DemandAcknowledged($this->id, $at)];
    }

    public function applyDemandAcknowledged(DemandAcknowledged $event) : void
    {
        $this->isAcknowledged = true;
    }
}
