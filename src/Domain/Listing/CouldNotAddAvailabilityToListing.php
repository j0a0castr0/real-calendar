<?php

namespace App\Domain\Listing;

final class CouldNotAddAvailabilityToListing extends \DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function becauseSlotIsNotAvailable(Slot $slot): self
    {
        return new self(
            sprintf(
                'Could not add availability from "%s" to "%s" because that time is not available.',
                $slot->from()->format('Y-m-d H:i'),
                $slot->to()->format('Y-m-d H:i')
            )
        );
    }
}
