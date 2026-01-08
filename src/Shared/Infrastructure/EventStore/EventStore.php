<?php

namespace App\Shared\Infrastructure\EventStore;

use App\Shared\Domain\DomainEvent;

interface EventStore
{
    /** @return DomainEvent[] */
    public function load(string $aggregateId): array;

    /** @param DomainEvent[] $events */
    public function append(string $aggregateId, array $events, int $expectedVersion): void;
}
