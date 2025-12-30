<?php

namespace App\Domain\Listing;

use App\Domain\AbstractDomainEvent;
use App\Domain\AggregateId;
use App\Domain\DomainEvent;

final class AvailabilitySlotWasAdded extends AbstractDomainEvent
{
    public function __construct(AggregateId $id, private Slot $slot)
    {
        parent::__construct($id);
    }

    public function slot(): Slot
    {
        return $this->slot;
    }


    public function serialize(): array
    {
        return [
            'aggregate_id' => (string) $this->aggregateId(),
            'from' => $this->slot->from()->format('c'),
            'to' => $this->slot->to()->format('c'),
            'occurred_at' => $this->occurredAt()->format('c'),
        ];
    }

    public static function deserialize(array $serialized): DomainEvent
    {
        $slot = new Slot(
            new \DateTimeImmutable($serialized['from']),
            new \DateTimeImmutable($serialized['to'])
        );

        return new self(
            new AggregateId($serialized['aggregate_id']),
            $slot
        );
    }
}
