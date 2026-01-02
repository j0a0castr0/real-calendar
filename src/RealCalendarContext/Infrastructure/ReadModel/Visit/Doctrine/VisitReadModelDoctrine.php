<?php

namespace App\RealCalendarContext\Infrastructure\ReadModel\Visit\Doctrine;

use App\RealCalendarContext\Application\ReadModel\Visit\Visit;
use App\RealCalendarContext\Application\ReadModel\Visit\VisitReadModel;
use App\RealCalendarContext\Infrastructure\ReadModel\Visit\Doctrine\Entity\VisitDoctrineDTO;
use Doctrine\ORM\EntityManagerInterface;

final class VisitReadModelDoctrine implements VisitReadModel
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    /**
     * @return Visit[]
     */
    public function getByAgent(string $agent): array
    {
        $visitsDto = $this->em->getRepository(VisitDoctrineDTO::class)->findBy(
            ['agent' => $agent]
        );

        return array_map(
            fn(VisitDoctrineDTO $visitDto) => new Visit(
                $visitDto->id,
                $visitDto->listingId,
                $visitDto->agent,
                $visitDto->visitorName,
                $visitDto->visitorEmail,
                $visitDto->visitorPhone,
                $visitDto->visitTime
            ),
            $visitsDto
        );
    }
}
