<?php

namespace App\Domain\Listing;

use App\Domain\AggregateRoot;
use App\Domain\AggregateId;
use Ramsey\Uuid\UuidInterface;

final class Listing extends AggregateRoot
{
    public static function create(?AggregateId $id = null) : self
    {
        $listing = new self();

        $listing->raise(new ListingWasCreated($id ?? new AggregateId()));

        return $listing;
    }

    public function addAvailability(\DateTimeImmutable $from, \DateTimeImmutable $to) : void
    {
        $this->raise(new AvailabiltyWasSet($from, $to));
    }

    protected function applyListingWasCreated(ListingWasCreated $event): void
    {
        $this->id = $event->aggregateId();
    }

    protected function applyAvailabiltyWasSet(AvailabiltyWasSet $event): void
    {

    }
}
