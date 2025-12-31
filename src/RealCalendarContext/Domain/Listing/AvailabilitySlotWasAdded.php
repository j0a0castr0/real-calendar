<?php

namespace App\RealCalendarContext\Domain\Listing;

use App\RealCalendarContext\Domain\AbstractDomainEvent;
use App\RealCalendarContext\Domain\AggregateId;
use App\RealCalendarContext\Domain\DomainEvent;

final class AvailabilitySlotWasAdded extends AbstractDomainEvent
{
    public function __construct(AggregateId $id, private Slot $slot, private Agent $agent)
    {
        parent::__construct($id);
    }

    public function agent(): Agent
    {
        return $this->agent;
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
            'agent' => (string) $this->agent
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
            $slot,
            new Agent($serialized['agent'])
        );
    }
}
