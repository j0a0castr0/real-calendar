<?php

namespace App\Application\Commands\AddAvailabilitySlotCommand;

use App\Application\Commands\CommandResult;
use App\Domain\AggregateId;
use App\Domain\Listing\ListingRepository;
use App\Domain\Listing\ListingWasNotFoundException;
use App\Domain\Listing\Slot;
use App\Domain\Listing\CouldNotAddAvailabilityToListing;
use Throwable;

final class AddAvailabilitySlotCommandHandler
{
    public function __construct(private readonly ListingRepository $repository) {}

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
        } catch (\InvalidArgumentException $e) {
            return CommandResult::notFound(sprintf("Listing %s not found.", $command->listingId));
        } catch (CouldNotAddAvailabilityToListing $e) {
            return CommandResult::domainError($e->getMessage());
        } catch (\Exception $e) {
            return CommandResult::failure($e->getMessage());
        }
    }
}
