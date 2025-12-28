<?php

namespace App\Infrastrucutre\Repository;

use App\Domain\AggregateRoot;
use App\Infrastrucutre\EventStore\EventStore;

abstract class AbstractEventSourcedRepository
{
    public function __construct(
        private EventStore $eventStore,
    ) {}

    public function get(string $id): AggregateRoot
    {
        $events = $this->eventStore->load($id);

        if (empty($events)) {
            throw new \RuntimeException('Aggregate not found');
        }

        return $this->aggregateClass()::reconstitute($id, $events);
    }

    public function save(AggregateRoot $aggregate): void
    {
        $events = $aggregate->popRecordedEvents();

        if ($events === []) {
            return;
        }

        $this->eventStore->append($aggregate->id(), $events);
    }

    public abstract function aggregateClass(): string;
}
