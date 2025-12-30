<?php

namespace App\Infrastructure\ReadModel\Listing\Doctrine;

use App\Application\ReadModel\Listing\Listing;
use App\Application\ReadModel\Listing\ListingReadModel;
use App\Infrastructure\ReadModel\Listing\Doctrine\Entity\ListingDoctrineDTO;
use Doctrine\ORM\EntityManagerInterface;

final class ListingReadModelDoctrine implements ListingReadModel
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function getAll(): array
    {
        $listingsDto = $this->em->getRepository(ListingDoctrineDTO::class)->findAll();

        return array_map(fn(ListingDoctrineDTO $listingDto) => new Listing(
            $listingDto->id,
        ), $listingsDto);
    }
}
