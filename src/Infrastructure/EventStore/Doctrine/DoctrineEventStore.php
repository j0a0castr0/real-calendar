<?php

namespace App\Infrastructure\EventStore\Doctrine;

use App\Domain\DomainEvent;
use App\Infrastructure\EventStore\Doctrine\Entity\EventDTO;
use App\Infrastructure\EventStore\EventStore;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineEventStore implements EventStore
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /** @return DomainEvent[] */
    public function load(string $aggregateId): array
    {
        $repo = $this->em->getRepository(EventDTO::class);
        $storedEvents = $repo->findBy(['aggregateId' => $aggregateId], ['occurredOn' => 'ASC']);

        $events = [];
        foreach ($storedEvents as $stored) {
            $events[] = $stored->getEvent();
        }

        return $events;
    }

    /** @param DomainEvent[] $events */
    public function append(string $aggregateId, array $events, int $expectedVersion): void
    {
        $repo = $this->em->getRepository(EventDTO::class);

        // Get the current max version for this aggregate
        $currentVersion = $repo->createQueryBuilder('e')
            ->select('MAX(e.version)')
            ->where('e.aggregateId = :aggregateId')
            ->setParameter('aggregateId', $aggregateId)
            ->getQuery()
            ->getSingleScalarResult();

        $currentVersion = $currentVersion !== null ? (int) $currentVersion : 0;

        if ($currentVersion !== $expectedVersion) {
            throw new \RuntimeException('Version conflict detected for aggregate ' . $aggregateId);
        }

        foreach ($events as $event) {
            $currentVersion++;
            $stored = new EventDTO($aggregateId, $event, $currentVersion);
            $this->em->persist($stored);
        }

        $this->em->flush();
    }
}
