<?php

namespace App\RealCalendarContext\Infrastructure\Projection\ListingSlots\Doctrine;

use App\RealCalendarContext\Domain\Listing\AvailabilitySlotWasAdded;
use App\RealCalendarContext\Infrastructure\ReadModel\ListingSlots\Doctrine\Entity\ListingSlotsDoctrineDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final class ListingSlotsProjection
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[AsMessageHandler]
    public function onAvailabilitySlotWasAdded(AvailabilitySlotWasAdded $event): void
    {
        $listSlotDoctrineDTO = new ListingSlotsDoctrineDTO();
        $listSlotDoctrineDTO->listingId = (string) $event->aggregateId();
        $listSlotDoctrineDTO->agent = (string) $event->agent();
        $listSlotDoctrineDTO->from = $event->slot()->from();
        $listSlotDoctrineDTO->to = $event->slot()->to();
        $this->em->persist($listSlotDoctrineDTO);
        $this->em->flush();
    }
}
