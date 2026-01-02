<?php

namespace App\RealCalendarContext\Infrastructure\ReadModel\Visit\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'visit_read')]
class VisitDoctrineDTO
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    public string $id;

    #[ORM\Column(type: 'string', length: 36)]
    public string $listingId;

    #[ORM\Column(type: 'string', length: 255)]
    public string $agent;

    #[ORM\Column(type: 'string', length: 255)]
    public string $visitorName;

    #[ORM\Column(type: 'string', length: 255)]
    public string $visitorEmail;

    #[ORM\Column(type: 'string', length: 20)]
    public string $visitorPhone;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $visitStartTime;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $visitEndTime;
}
