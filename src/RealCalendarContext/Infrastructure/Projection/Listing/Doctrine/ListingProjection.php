<?php

namespace App\RealCalendarContext\Infrastructure\Projection\Listing\Doctrine;

use App\RealCalendarContext\Domain\Listing\ListingWasCreated;
use App\RealCalendarContext\Infrastructure\ReadModel\Listing\Doctrine\Entity\ListingDoctrineDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final class ListingProjection
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[AsMessageHandler]
    public function onListingWasCreated(ListingWasCreated $event): void
    {
        $listingDoctrine = new ListingDoctrineDTO();
        $listingDoctrine->id = (string) $event->aggregateId();
        $listingDoctrine->propertyReference = (string) $event->propertyReference();
        $listingDoctrine->agent = (string) $event->agent();

        $this->em->persist($listingDoctrine);
        $this->em->flush();
    }
}
