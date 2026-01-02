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


    public function hasSlotCovering(\DateTimeImmutable $instant): bool
    {
        foreach ($this->slots as $slot) {
            if ($instant >= $slot->from() && $instant < $slot->to()) {
                return true;
            }
        }

        return false;
    }

    public function slotCovering(\DateTimeImmutable $instant): Slot
    {
        foreach ($this->slots as $slot) {
            if ($instant >= $slot->from() && $instant < $slot->to()) {
                return $slot;
            }
        }

        throw CouldNotFindSlot::covering($instant);
    }

    public function removeSlot(Slot $slotToRemove): self
    {
        $slots = array_filter(
            $this->slots,
            fn(Slot $slot) => !$slot->equals($slotToRemove)
        );

        return new self(array_values($slots));
    }

    public function serialize() : array
    {
        return array_map(fn(Slot $slot) => $slot->serialize(), $this->slots);
    }

    public static function deserialize(array $serialized) : self
    {
        return new self(
            array_map(fn(array $slotSerialized) => Slot::deserialize($slotSerialized), $serialized)
        );
    }

    /**
     * @return Slot[]
     */
    public function slots(): array
    {
        return $this->slots;
    }
}
