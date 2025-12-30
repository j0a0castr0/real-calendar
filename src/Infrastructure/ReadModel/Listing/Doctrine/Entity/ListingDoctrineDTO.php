<?php

namespace App\Infrastructure\ReadModel\Listing\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'listing_read')]
class ListingDoctrineDTO
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    public string $id;
}
