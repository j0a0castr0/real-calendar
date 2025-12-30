<?php

namespace App\Application\Queries\GetListingsQuery;

use App\Application\ReadModel\Listing\Listing;
use App\Application\ReadModel\Listing\ListingReadModel;

final class GetListingsQueryHandler
{
    public function __construct(private readonly ListingReadModel $listingReadModel) {}

    /**
     * @return Listing[]
     */
    public function handle(GetListingsQuery $query) : array
    {
        return $this->listingReadModel->getAll();
    }
}
