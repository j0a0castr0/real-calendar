<?php

namespace App\Domain\Listing;
use App\Domain\AbstractDomainEvent;
use App\Domain\AggregateId;
use App\Domain\DomainEvent;

final class ListingWasCreated extends AbstractDomainEvent
{

    public function serialize(): array
    {
        return [
            'aggregate_id' => (string) $this->aggregateId(),
            'occurred_at' => $this->occurredAt()->format('c'), // ISO 8601
        ];
    }

    public static function deserialize(array $serialized): DomainEvent
    {
        return new self(
            new AggregateId($serialized['aggregate_id']),
            new \DateTimeImmutable($serialized['occurred_at'])
        );
    }
}
