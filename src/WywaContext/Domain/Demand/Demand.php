<?php

namespace App\WywaContext\Domain\Demand;

use App\Shared\Domain\AggregateId;
use App\Shared\Domain\AggregateRoot;

final class Demand extends AggregateRoot
{
    public static function receive(\DateTimeImmutable $at, ?AggregateId $id = null): self
    {
        return self::reconstitute($aggregateId = $id ?? new AggregateId(), [])
            ->withEvents([
                new DemandReceived($aggregateId, $at),
            ]);
    }

    public function applyDemandReceived(DemandReceived $event): void
    {
        $this->id = $event->aggregateId();
    }
}
