<?php

namespace App\RealCalendarContext\Infrastructure\EventStore\Doctrine;

use App\RealCalendarContext\Infrastructure\EventStore\Doctrine\Entity\EventDTO;
use App\Shared\Domain\DomainEvent;
use App\Shared\Infrastructure\EventStore\EventStore;
use App\Shared\Infrastructure\EventUpcasting\EventUpcasterRegistry;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineEventStore implements EventStore
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventUpcasterRegistry $upcasters
    ) { }

    /** @return DomainEvent[] */
    public function load(string $aggregateId): array
    {
        $repo = $this->em->getRepository(EventDTO::class);
        $storedEvents = $repo->findBy(['aggregateId' => $aggregateId], ['version' => 'ASC']);

        $events = [];
        foreach ($storedEvents as $stored) {
            $eventClass = $stored->eventClass();
            $payload = $stored->payload();

            if (!is_subclass_of($eventClass, DomainEvent::class)) {
                throw new \RuntimeException("Class {$eventClass} does not implement DomainEvent");
            }

            $from = $stored->eventSchemaVersion();
            $to = $eventClass::schemaVersion();

            if ($from < $to) {
                $payload = $this->upcasters->upcast($eventClass, $from, $to, $payload);
            }

            if ($from > $to) {
                throw new \RuntimeException(
                    "Stored event schema v{$from} is newer than code expects v{$to} for {$eventClass}"
                );
            }

            $events[] = $eventClass::deserialize($payload);
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
