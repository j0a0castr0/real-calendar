<?php

namespace App\Domain\Listing;

use App\Domain\AggregateId;

interface ListingRepository
{
    public function get(AggregateId $id): Listing;
    public function save(Listing $listing): void;
}
