<?php

namespace App\Application\ReadModel\ListingSlots;

interface ListingSlotsReadModel
{
    /**
     * @return ListSlot[]
     */
    public function getAllForListing(string $id) :array;
}
