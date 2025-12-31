<?php

namespace App\RealCalendarContext\Domain\Listing;

final class Availabilty
{
    public function __construct(private readonly array $slots = [])
    {
    }

    public function ensureSlotIsAvailableForAddition(Slot $slot) : void
    {
        foreach ($this->slots as $currentSlot) {;
            if ($currentSlot->overlaps($slot)) {
                throw CouldNotAddAvailabilityToListing::becauseSlotIsNotAvailable($slot);
            }
        }
    }

    public function add(Slot $slot) : self
    {
        return new self([...$this->slots, $slot]);
    }
}
