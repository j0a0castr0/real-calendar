<?php

namespace App\Infrastructure\EventStore;

use App\Domain\DomainEvent;

interface EventStore
{
    /** @return DomainEvent[] */
    public function load(string $aggregateId): array;

    /** @param DomainEvent[] $events */
    public function append(string $aggregateId, array $events, int $expectedVersion): void;
}
