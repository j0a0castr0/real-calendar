<?php

namespace App\RealCalendarContext\Domain\Visit;

final class ListingId
{
    public function __construct(
        private string $listingId
    ) { }

    public function __toString(): string
    {
        return $this->listingId;
    }
}
