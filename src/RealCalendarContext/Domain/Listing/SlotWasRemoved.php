<?php

namespace App\RealCalendarContext\Domain\Listing;

use App\RealCalendarContext\Domain\AbstractDomainEvent;
use App\RealCalendarContext\Domain\AggregateId;
use App\RealCalendarContext\Domain\DomainEvent;

class SlotWasRemoved extends AbstractDomainEvent
{
    public function __construct(
        AggregateId $id,
        private Slot $removedSlot,
        private Availabilty $currentAvailability,
    ) {
        parent::__construct($id);
    }

    public function removedSlot(): Slot
    {
        return $this->removedSlot;
    }

    public function currentAvailability(): Availabilty
    {
        return $this->currentAvailability;
    }

    public function serialize(): array
    {
        return [
            'aggregate_id' => (string) $this->aggregateId(),
            'removed_slot' => $this->removedSlot->serialize(),
            'current_availability' => $this->currentAvailability->serialize(),
            'occurred_at' => $this->occurredAt()->format('c'),
        ];
    }

    public static function deserialize(array $serialized): DomainEvent
    {
        return new self(
            new AggregateId($serialized['aggregate_id']),
            Slot::deserialize($serialized['removed_slot']),
            Availabilty::deserialize($serialized['current_availability']),
        );
    }
}
