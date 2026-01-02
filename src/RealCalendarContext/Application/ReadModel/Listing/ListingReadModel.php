<?php

namespace App\RealCalendarContext\Application\ReadModel\Listing;

interface ListingReadModel
{
    /**
     * @return Listing[]
     */
    public function getAllForAgent(string $agent) :array;

    public function get(string $id): Listing;
}
