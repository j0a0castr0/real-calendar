<?php

namespace App\RealCalendarContext\Infrastructure\ReadModel\ListingSlots\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'listing_slots_read')]
class ListingSlotsDoctrineDTO
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(type: 'string')]
    public string $listingId;

    #[ORM\Column(name: 'from_date', type: 'datetime_immutable')]
    public ?\DateTimeImmutable $from;

    #[ORM\Column(name: 'to_date', type: 'datetime_immutable')]
    public ?\DateTimeImmutable $to;
}
