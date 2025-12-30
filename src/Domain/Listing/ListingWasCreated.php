<?php

namespace App\Domain\Listing;
use App\Domain\AbstractDomainEvent;
use App\Domain\AggregateId;
use App\Domain\DomainEvent;
use DateTime;

final class ListingWasCreated extends AbstractDomainEvent
{
    public function __construct(
        AggregateId $id,
        private PropertyReference $propertyReference,
        private Agent $agent
    ) {
        parent::__construct($id);
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
}
