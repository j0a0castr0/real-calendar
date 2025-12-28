<?php

namespace App\Infrastrucutre\EventStore\InMemory;

use App\Domain\DomainEvent;
use App\Infrastrucutre\EventStore\EventStore;

final class InMemoryEventStore implements EventStore
{
    private array $events = [];

    public function load(string $aggregateId): array
    {
        if (!isset($this->events[$aggregateId])) {
            return [];
        }

        return array_map([$this, 'deserialize'], $this->events[$aggregateId]);
    }

    public function append(string $aggregateId, array $events): void
    {
        foreach ($events as $event) {
            $this->events[$aggregateId][] = [
                'event_type' => $event->eventType(),
                'payload' => json_encode($event->payload()),
                'occurred_at' => $event->occurredAt()->format('Y-m-d H:i:s'),
                'aggregate_id' => $aggregateId,
            ];
        }
    }

    private function deserialize(array $row): DomainEvent
    {
        $class = $row['event_type'];
        return new $class(
            $row['aggregate_id'],
            ...array_values(json_decode($row['payload'], true))
        );
    }
}

