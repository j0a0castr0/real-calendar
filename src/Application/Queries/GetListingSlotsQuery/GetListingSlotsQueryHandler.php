<?php

namespace App\Application\Queries\GetListingSlotsQuery;

use App\Application\ReadModel\Listing\ListingReadModel;
use App\Application\ReadModel\ListingSlots\ListingSlot;
use App\Application\ReadModel\ListingSlots\ListingSlotsReadModel;

class GetListingSlotsQueryHandler
{
    public function __construct(private readonly ListingSlotsReadModel $listingSlotsReadModel) {}

    /**
     * @return ListingSlot[]
     */
    public function handle(GetListingSlotsQuery $query) : array
    {
        return $this->listingSlotsReadModel->getAllForListing($query->listingId);
    }
}
