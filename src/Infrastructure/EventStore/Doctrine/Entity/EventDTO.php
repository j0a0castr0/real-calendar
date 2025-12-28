<?php

namespace App\Infrastructure\EventStore\Doctrine\Entity;

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

    public function __construct(string $aggregateId, object $event, int $version)
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

    public function getEvent(): object
    {
        return $this->deserializeEvent($this->eventClass, $this->payload);
    }

    private function serializeEvent(object $event): array
    {
        // Simple payload: convert public properties to array
        return (array) $event;
    }

    private function deserializeEvent(string $class, array $payload): object
    {
        return new $class(...array_values($payload));
    }
}
