<?php

namespace App\Application\Commands\CreateListingCommand;

use App\Application\Commands\CommandResult;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepository;

final class CreateListingCommandHandler
{
    public function __construct(private readonly ListingRepository $repository) {}

    public function handle(CreateListingCommand $command): CommandResult
    {
        $listing = Listing::create();
        $this->repository->save($listing);

        return CommandResult::success(
            ['id' => (string )$listing->id()],
        );
    }
}
