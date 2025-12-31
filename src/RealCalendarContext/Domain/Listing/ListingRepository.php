<?php

namespace App\RealCalendarContext\Domain\Listing;

use App\RealCalendarContext\Domain\AggregateId;

interface ListingRepository
{
    public function get(AggregateId $id): Listing;
    public function save(Listing $listing): void;
}
