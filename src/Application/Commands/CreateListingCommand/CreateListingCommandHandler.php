<?php

namespace App\Application\Commands\CreateListingCommand;

use App\Application\Commands\CommandResult;
use App\Domain\Listing\Agent;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepository;
use App\Domain\Listing\PropertyReference;

final class CreateListingCommandHandler
{
    public function __construct(private readonly ListingRepository $repository) {}

    public function handle(CreateListingCommand $command): CommandResult
    {
        $listing = Listing::create(
            new PropertyReference($command->propertyReference),
            new Agent($command->agent)
        );
        $this->repository->save($listing);

        return CommandResult::success(
            ['id' => (string )$listing->id()],
        );
    }
}
