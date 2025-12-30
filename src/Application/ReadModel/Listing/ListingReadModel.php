<?php

namespace App\Application\ReadModel\Listing;

interface ListingReadModel
{
    /**
     * @return Listing[]
     */
    public function getAll() :array;
}
