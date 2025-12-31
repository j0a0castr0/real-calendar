<?php

namespace App\Infrastructure\ReadModel\ListingSlots\Doctrine;

use App\Application\ReadModel\ListingSlots\ListingSlot;
use App\Application\ReadModel\ListingSlots\ListingSlotsReadModel;
use App\Application\ReadModel\ListingSlots\ListSlot;
use App\Infrastructure\ReadModel\ListingSlots\Doctrine\Entity\ListingSlotsDoctrineDTO;
use Doctrine\ORM\EntityManagerInterface;

final class ListingSlotsReadModelDoctrine implements ListingSlotsReadModel
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function getAllForListing(string $id): array
    {
        $listingsDto = $this->em->getRepository(ListingSlotsDoctrineDTO::class)->findBy([
            'listingId' => $id
        ], [
            'from' => 'ASC'
        ]);

        return array_map(
            fn(ListingSlotsDoctrineDTO $dto) => new ListingSlot(
                $dto->from,
                $dto->to,
            ),
            $listingsDto
        );
    }
}
