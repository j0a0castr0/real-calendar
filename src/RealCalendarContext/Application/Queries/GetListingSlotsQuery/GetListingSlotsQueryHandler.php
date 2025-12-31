<?php

namespace App\RealCalendarContext\Application\Queries\GetListingSlotsQuery;

use App\RealCalendarContext\Application\ReadModel\ListingSlots\ListingSlot;
use App\RealCalendarContext\Application\ReadModel\ListingSlots\ListingSlotsReadModel;

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
