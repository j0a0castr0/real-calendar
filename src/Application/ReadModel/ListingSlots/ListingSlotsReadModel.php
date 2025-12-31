<?php

namespace App\Application\ReadModel\ListingSlots;

interface ListingSlotsReadModel
{
    /**
     * @return ListingSlot[]
     */
    public function getAllForListing(string $id) :array;
}
