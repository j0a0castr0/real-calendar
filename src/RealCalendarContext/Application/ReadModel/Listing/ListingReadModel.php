<?php

namespace App\RealCalendarContext\Application\ReadModel\Listing;

interface ListingReadModel
{
    /**
     * @return Listing[]
     */
    public function getAll() :array;

    public function get(string $id): Listing;
}
