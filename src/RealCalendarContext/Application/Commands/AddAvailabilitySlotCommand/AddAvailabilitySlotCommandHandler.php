<?php

namespace App\RealCalendarContext\Application\Commands\AddAvailabilitySlotCommand;

use App\RealCalendarContext\Application\Commands\CommandResult;
use App\RealCalendarContext\Domain\AggregateId;
use App\RealCalendarContext\Domain\Listing\CouldNotAddAvailabilityToListing;
use App\RealCalendarContext\Domain\Listing\ListingRepository;
use App\RealCalendarContext\Domain\Listing\ListingWasNotFoundException;
use App\RealCalendarContext\Domain\Listing\Slot;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
