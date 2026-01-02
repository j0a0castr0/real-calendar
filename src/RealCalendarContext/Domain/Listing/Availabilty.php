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

    public function isThereSlotStartingAt(\DateTimeImmutable $date) : bool
    {
        return array_reduce(
            $this->slots,
            fn(bool $carry, Slot $slot) => $carry || ($date >= $slot->from() && $date < $slot->to()),
            false
        );
    }

    public function remove(\DateTimeImmutable $date) : self
    {
        $slots = array_filter(
            $this->slots,
            fn(Slot $slot) => !($date >= $slot->from() && $date < $slot->to())
        );

        return new self(array_values($slots));
    }
}
