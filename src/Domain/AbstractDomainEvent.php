<?php

namespace App\Domain;

abstract class AbstractDomainEvent implements DomainEvent
{
    protected string $aggregateId;
    protected \DateTimeImmutable $occurredAt;

    public function __construct(string $aggregateId)
    {
        $this->aggregateId = $aggregateId;
        $this->occurredAt = new \DateTimeImmutable();
    }

    public function aggregateId(): string
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

