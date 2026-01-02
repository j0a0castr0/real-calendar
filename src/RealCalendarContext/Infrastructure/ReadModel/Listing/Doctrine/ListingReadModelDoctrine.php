<?php

namespace App\RealCalendarContext\Infrastructure\ReadModel\Listing\Doctrine;

use App\RealCalendarContext\Application\ReadModel\Listing\Listing;
use App\RealCalendarContext\Application\ReadModel\Listing\ListingReadModel;
use App\RealCalendarContext\Infrastructure\ReadModel\Listing\Doctrine\Entity\ListingDoctrineDTO;
use Doctrine\ORM\EntityManagerInterface;

final class ListingReadModelDoctrine implements ListingReadModel
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function getAll(): array
    {
        $listingsDto = $this->em->getRepository(ListingDoctrineDTO::class)->findAll();

        return array_map(fn(ListingDoctrineDTO $listingDto) => new Listing(
            $listingDto->id,
            $listingDto->propertyReference,
            $listingDto->agent,
        ), $listingsDto);
    }

    public function get(string $id): Listing
    {
        $listingDto = $this->em->getRepository(ListingDoctrineDTO::class)->find($id);

        return new Listing(
            $listingDto->id,
            $listingDto->propertyReference,
            $listingDto->agent,
        );
    }
}
