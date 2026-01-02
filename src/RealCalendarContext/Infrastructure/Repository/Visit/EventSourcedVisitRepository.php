<?php

namespace App\RealCalendarContext\Infrastructure\Repository\Visit;

use App\RealCalendarContext\Domain\AggregateId;
use App\RealCalendarContext\Domain\Visit\Visit;
use App\RealCalendarContext\Domain\Visit\VisitRepository;
use App\RealCalendarContext\Domain\Visit\VisitWasNotFoundException;
use App\RealCalendarContext\Infrastructure\EventStore\EventStore;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventSourcedVisitRepository implements VisitRepository
{
    public function __construct(
        private EventStore $eventStore,
        private MessageBusInterface $messageBus
    ) {}

    public function get(AggregateId $id): Visit
    {
        $events = $this->eventStore->load((string)$id);

        if (empty($events)) {
            throw new VisitWasNotFoundException(sprintf('Visit with id "%s" was not found.', $id));
        }

        return Visit::reconstitute($id, $events);
    }

    public function save(Visit $listing): void
    {
        $events = $listing->popRecordedEvents();
        if (empty($events)) return;

        $expectedVersion = $listing->version() - count($events);

        $this->eventStore->append((string)$listing->id(), $events, $expectedVersion);
        foreach ($events as $event) {
            $this->messageBus->dispatch($event);
        }
    }
}
