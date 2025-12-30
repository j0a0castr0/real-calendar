<?php

namespace App\Infrastructure\EventStore\Doctrine\Entity;

use App\Domain\DomainEvent;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'events')]
class EventDTO
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $aggregateId;

    #[ORM\Column(type: 'string')]
    private string $eventClass;

    #[ORM\Column(type: 'json')]
    private array $payload;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredAt;

    #[ORM\Column(type: 'integer')]
    private int $version;

    public function __construct(string $aggregateId, DomainEvent $event, int $version)
    {
        $this->aggregateId = $aggregateId;
        $this->eventClass = get_class($event);
        $this->payload = $this->serializeEvent($event);
        $this->occurredAt = new \DateTimeImmutable();
        $this->version = $version;
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getEvent(): DomainEvent
    {
        return $this->deserializeEvent($this->eventClass, $this->payload);
    }

    private function serializeEvent(DomainEvent $event): array
    {
        return $event->serialize();
    }

    private function deserializeEvent(string $class, array $payload): DomainEvent
    {
        if (!is_subclass_of($class, DomainEvent::class)) {
            throw new \RuntimeException("Class {$class} does not implement DomainEvent");
        }

        return $class::deserialize($payload);
    }
}
