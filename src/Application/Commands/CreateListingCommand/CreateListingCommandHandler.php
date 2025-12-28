<?php

namespace App\Application\Commands\CreateListingCommand;

use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepository;

final class CreateListingCommandHandler
{
    public function __construct(private readonly ListingRepository $repository) {}

    public function handle(CreateListingCommand $command): void
    {
        $listing = Listing::create();
        $this->repository->save($listing);
    }
}
