<?php

namespace App\Infrastructure\Repository\Listing;

use App\Domain\AggregateId;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepository;
use App\Domain\Listing\ListingWasNotFoundException;
use App\Infrastructure\EventStore\EventStore;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventSourcedListingRepository implements ListingRepository
{
    public function __construct(
        private EventStore $eventStore,
        private MessageBusInterface $messageBus
    ) {}

    public function get(AggregateId $id): Listing
    {
        $events = $this->eventStore->load((string)$id);

        if (empty($events)) {
            throw new ListingWasNotFoundException(sprintf('Listing with id "%s" was not found.', $id));
        }

        return Listing::reconstitute($id, $events);
    }

    public function save(Listing $listing): void
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
