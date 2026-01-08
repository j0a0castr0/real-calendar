<?php

namespace App\RealCalendarContext\Application\ProcessManager;

use App\RealCalendarContext\Domain\Listing\ListingRepository;
use App\RealCalendarContext\Domain\Listing\ListingWasNotFoundException;
use App\RealCalendarContext\Domain\Visit\VisitWasScheduled;
use App\Shared\Domain\AggregateId;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final class MakeSlotUnavailableWhenVisitScheduled
{
    public function __construct(
        private ListingRepository $listingRepository,
        private readonly LoggerInterface $logger = new NullLogger()
    ) {
    }

    #[AsMessageHandler]
    public function onVisitWasScheduled(VisitWasScheduled $event): void
    {
        try {
            $listing = $this->listingRepository->get(new AggregateId($event->listingId()));

            $listing->removeSlot(
                $event->visitTime()->start()
            );

            $this->listingRepository->save($listing);

        } catch (ListingWasNotFoundException $e) {
            $this->logger->error(sprintf("[MakeSlotUnavailableWhenVisitScheduled] Listing %s not found ", (string) $event->aggregateId()));
        }
    }
}
