<?php

namespace App\RealCalendarContext\Domain\Listing;
use App\RealCalendarContext\Domain\AbstractDomainEvent;
use App\RealCalendarContext\Domain\AggregateId;
use App\RealCalendarContext\Domain\DomainEvent;

final class ListingWasCreated extends AbstractDomainEvent
{
    public function __construct(
        AggregateId $id,
        private PropertyReference $propertyReference,
        private Agent $agent
    ) {
        parent::__construct($id);
    }

    public function propertyReference(): PropertyReference
    {
        return $this->propertyReference;
    }

    public function agent(): Agent
    {
        return $this->agent;
    }

    public function serialize(): array
    {
        return [
            'aggregate_id' => (string) $this->aggregateId(),
            'occurred_at' => $this->occurredAt()->format('c'), // ISO 8601
            'agent' => (string) $this->agent,
            'property_reference' => (string) $this->propertyReference,
        ];
    }

    public static function deserialize(array $serialized): DomainEvent
    {
        $self = new self(
            new AggregateId($serialized['aggregate_id']),
            new PropertyReference($serialized['property_reference']),
            new Agent($serialized['agent']),
        );
        $self->occurredAt = new \DateTimeImmutable($serialized['occurred_at']);

        return $self;
    }

    public function getPropertyReference(): PropertyReference
    {
        return $this->propertyReference;
    }
}
