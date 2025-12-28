<?php

namespace App\Domain;

abstract class AbstractDomainEvent implements DomainEvent
{
    protected AggregateId $aggregateId;
    protected \DateTimeImmutable $occurredAt;

    public function __construct(AggregateId $aggregateId)
    {
        $this->aggregateId = $aggregateId;
        $this->occurredAt = new \DateTimeImmutable();
    }

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

