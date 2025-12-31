<?php

namespace App\RealCalendarContext\Application\Commands\CreateListingCommand;

use App\RealCalendarContext\Application\Commands\CommandResult;
use App\RealCalendarContext\Domain\Listing\Agent;
use App\RealCalendarContext\Domain\Listing\Listing;
use App\RealCalendarContext\Domain\Listing\ListingRepository;
use App\RealCalendarContext\Domain\Listing\PropertyReference;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class CreateListingCommandHandler
{
    public function __construct(
        private readonly ListingRepository $repository,
        private readonly LoggerInterface $logger = new NullLogger()
    ) {}

    public function handle(CreateListingCommand $command): CommandResult
    {
        try {
            $listing = Listing::create(
                new PropertyReference($command->propertyReference),
                new Agent($command->agent)
            );
            $this->repository->save($listing);

            return CommandResult::success(
                ['id' => (string )$listing->id()],
            );
        } catch (\InvalidArgumentException $e) {
            return CommandResult::domainError($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error('Failed to add availability slot', ['exception' => $e]);
            return CommandResult::failure($e->getMessage());
        }

    }
}
