<?php

namespace App\RealCalendarContext\Infrastructure\Projection\Visit\Doctrine;

use App\RealCalendarContext\Domain\Visit\VisitWasScheduled;
use App\RealCalendarContext\Infrastructure\ReadModel\Visit\Doctrine\Entity\VisitDoctrineDTO;
use App\RealCalendarContext\Infrastructure\ReadModel\Listing\Doctrine\Entity\ListingDoctrineDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

final class VisitProjection
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger
    ) {
    }

    #[AsMessageHandler]
    public function onVisitWasScheduled(VisitWasScheduled $event): void
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('l.agent')
            ->from(ListingDoctrineDTO::class, 'l')
            ->where('l.id = :id')
            ->setParameter('id', (string) $event->listingId());

        $agentResult = $qb->getQuery()->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);

        if (!$agentResult) {
            $this->logger->warning(sprintf(
                'Visit projection skipped: listing %s not found. Visit ID: %s',
                $event->listingId(),
                $event->aggregateId()
            ));
            return;
        }

        $visit = new VisitDoctrineDTO();
        $visit->id = (string) $event->aggregateId();
        $visit->listingId = (string) $event->listingId();
        $visit->agent = $agentResult['agent'];

        $visit->visitorName = $event->visitor()->nameAsString();
        $visit->visitorEmail = (string) $event->visitor()->contact()->email();
        $visit->visitorPhone = (string) $event->visitor()->contact()->phone();

        $visit->visitTime = $event->visitTime()->value();

        $this->em->persist($visit);
        $this->em->flush();
    }
}
