<?php

namespace App\RealCalendarContext\Domain\Listing;

use App\RealCalendarContext\Domain\AbstractDomainEvent;
use App\RealCalendarContext\Domain\AggregateId;
use App\RealCalendarContext\Domain\DomainEvent;

class SlotWasRemoved extends AbstractDomainEvent
{
    public function __construct(AggregateId $id, private \DateTimeImmutable $date)
    {
        parent::__construct($id);
    }

    public function serialize(): array
    {
        return [
            'aggregate_id' => (string) $this->aggregateId(),
            'date' => $this->date->format('c'),
            'occurred_at' => $this->occurredAt()->format('c'),
        ];
    }

    public static function deserialize(array $serialized): DomainEvent
    {
        return new self(
            AggregateId::fromString($serialized['aggregate_id']),
            new \DateTimeImmutable($serialized['date'])
        );
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }
}
