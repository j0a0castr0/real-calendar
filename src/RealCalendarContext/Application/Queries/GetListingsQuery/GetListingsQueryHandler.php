<?php

namespace App\RealCalendarContext\Application\Queries\GetListingsQuery;

use App\RealCalendarContext\Application\ReadModel\Listing\Listing;
use App\RealCalendarContext\Application\ReadModel\Listing\ListingReadModel;

final class GetListingsQueryHandler
{
    public function __construct(private readonly ListingReadModel $listingReadModel) {}

    /**
     * @return Listing[]
     */
    public function handle(GetListingsQuery $query) : array
    {
        return $this->listingReadModel->getAllForAgent($query->agent);
    }
}
