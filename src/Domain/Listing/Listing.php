<?php

namespace App\Domain\Listing;

use App\Domain\AggregateRoot;
use App\Domain\AggregateId;
use Ramsey\Uuid\UuidInterface;

final class Listing extends AggregateRoot
{
    private Availabilty $availability;
    private PropertyReference $propertyReference;
    private Agent $agent;

    private function __construct()
    {
    }

    public static function create(
        PropertyReference $propertyReference,
        Agent $agent,
        ?AggregateId $id = null
    ) : self {
        $listing = new self();

        $listing->raise(new ListingWasCreated(
            $id ?? new AggregateId(),
            $propertyReference,
            $agent
        ));

        return $listing;
    }

    public function addAvailabilitySlot(Slot $slot) : void
    {
        $this->availability->ensureSlotIsAvailableForAddition($slot);
        $this->raise(new AvailabilitySlotWasAdded($this->id, $slot, $this->agent));
    }

    protected function applyListingWasCreated(ListingWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        $this->availability = new Availabilty();
        $this->agent = $event->agent();
        $this->propertyReference = $event->propertyReference();
    }

    protected function applyAvailabilitySlotWasAdded(AvailabilitySlotWasAdded $event): void
    {
        $this->availability = $this->availability->add($event->slot());
    }
}
