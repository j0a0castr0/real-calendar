<?php

namespace App\Infrastrucutre\EventStore;

interface EventStore
{
    /** @return DomainEvent[] */
    public function load(string $aggregateId): array;

    /** @param DomainEvent[] $events */
    public function append(string $aggregateId, array $events): void;
}
