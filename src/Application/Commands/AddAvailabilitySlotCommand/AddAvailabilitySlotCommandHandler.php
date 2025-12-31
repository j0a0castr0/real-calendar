<?php

namespace App\Application\Commands\AddAvailabilitySlotCommand;

use App\Application\Commands\CommandResult;
use App\Domain\AggregateId;
use App\Domain\Listing\ListingRepository;
use App\Domain\Listing\ListingWasNotFoundException;
use App\Domain\Listing\Slot;
use App\Domain\Listing\CouldNotAddAvailabilityToListing;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

final class AddAvailabilitySlotCommandHandler
{
    public function __construct(
        private readonly ListingRepository $repository,
        private readonly LoggerInterface $logger = new NullLogger()
    ) {}

    public function handle(AddAvailabilitySlotCommand $command): CommandResult
    {
        try {
            $listing = $this->repository->get(new AggregateId($command->listingId));
            $slot = new Slot($command->from, $command->to);

            $listing->addAvailabilitySlot($slot);

            $this->repository->save($listing);

            return CommandResult::success(
                ['listingId' => $command->listingId],
                'Availability slot added successfully.'
            );
        } catch (ListingWasNotFoundException $e) {
            return CommandResult::notFound($e->getMessage());
        } catch (\InvalidArgumentException|CouldNotAddAvailabilityToListing $e) {
            return CommandResult::domainError($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error('Failed to add availability slot', ['exception' => $e]);
            return CommandResult::failure($e->getMessage());
        }
    }
}
