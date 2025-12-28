<?php

namespace App\Domain;

interface DomainEvent
{
    public function aggregateId(): AggregateId;
    public function occurredAt(): \DateTimeImmutable;
    public function eventType(): string;
    public function payload(): array;
}
