<?php

namespace App\Domain\Listing;

use App\Domain\AggregateRoot;
use App\Domain\AggregateId;
use Ramsey\Uuid\UuidInterface;

final class Listing extends AggregateRoot
{
    private Availabilty $availability;

    private function __construct()
    {
    }

    public static function create(?AggregateId $id = null) : self
    {
        $listing = new self();

        $listing->raise(new ListingWasCreated($id ?? new AggregateId()));

        return $listing;
    }

    public function addAvailabilitySlot(Slot $slot) : void
    {
        $this->availability->ensureSlotIsAvailableForAddition($slot);
        $this->raise(new AvailabilitySlotWasAdded($this->id, $slot));
    }

    protected function applyListingWasCreated(ListingWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        $this->availability = new Availabilty();
    }

    protected function applyAvailabilitySlotWasAdded(AvailabilitySlotWasAdded $event): void
    {
        $this->availability = $this->availability->add($event->slot());
    }
}
