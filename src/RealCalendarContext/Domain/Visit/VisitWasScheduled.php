<?php

namespace App\RealCalendarContext\Domain\Visit;

use App\RealCalendarContext\Domain\AbstractDomainEvent;
use App\RealCalendarContext\Domain\AggregateId;
use App\RealCalendarContext\Domain\DomainEvent;

class VisitWasScheduled extends AbstractDomainEvent
{
    public function __construct(
        AggregateId $id,
        private ListingId $listingId,
        private Visitor $visitor,
        private VisitTime $visitTime
    ){
        parent::__construct($id);
    }

    public function serialize(): array
    {
        return [
            'aggregate_id' => (string) $this->aggregateId(),
            'listing_id' => (string) $this->listingId,
            'visitor' => $this->visitor->serialize(),
            'visit_time' => $this->visitTime->serialize(),
        ];
    }

    public static function deserialize(array $serialized): DomainEvent
    {
        $self = new self(
            new AggregateId($serialized['aggregate_id']),
            new ListingId($serialized['listing_id']),
            Visitor::deserialize($serialized['visitor']),
            VisitTime::deserialize($serialized['visit_time']),
        );
        $self->occurredAt = new \DateTimeImmutable($serialized['occurred_at']);

        return $self;
    }

    public function listingId(): ListingId
    {
        return $this->listingId;
    }

    public function visitor(): Visitor
    {
        return $this->visitor;
    }

    public function visitTime(): VisitTime
    {
        return $this->visitTime;
    }
}
