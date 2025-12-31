<?php

namespace App\RealCalendarContext\Domain;

abstract class AbstractDomainEvent implements DomainEvent
{
    public function __construct(
        protected AggregateId $aggregateId,
        protected \DateTimeImmutable $occurredAt = new \DateTimeImmutable()
    ) {}

    public function aggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function eventType(): string
    {
        return static::class;
    }
}

