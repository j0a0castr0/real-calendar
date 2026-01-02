<?php

namespace App\RealCalendarContext\Infrastructure\Projection\ListingSlots\Doctrine;

use App\RealCalendarContext\Domain\Listing\AvailabilitySlotWasAdded;
use App\RealCalendarContext\Domain\Listing\SlotWasRemoved;
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
        $listSlotDoctrineDTO->from = $event->slot()->from();
        $listSlotDoctrineDTO->to = $event->slot()->to();
        $this->em->persist($listSlotDoctrineDTO);
        $this->em->flush();
    }

    #[AsMessageHandler]
    public function onSlotWasRemoved(SlotWasRemoved $event): void
    {
        $listingId = (string) $event->aggregateId();

        $this->em->createQueryBuilder()
            ->delete(ListingSlotsDoctrineDTO::class, 's')
            ->where('s.listingId = :listingId')
            ->setParameter('listingId', $listingId)
            ->getQuery()
            ->execute();

        foreach ($event->currentAvailability()->slots() as $slot) {
            $dto = new ListingSlotsDoctrineDTO();
            $dto->listingId = $listingId;
            $dto->from = $slot->from();
            $dto->to = $slot->to();

            $this->em->persist($dto);
        }

        $this->em->flush();
    }
}
