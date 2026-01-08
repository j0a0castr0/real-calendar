<?php

namespace App\RealCalendarContext\Infrastructure\EventStore\Doctrine\Entity;

use App\Shared\Domain\DomainEvent;
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

    #[ORM\Column(type: 'integer')]
    private int $eventSchemaVersion;

    public function __construct(string $aggregateId, DomainEvent $event, int $version)
    {
        $this->aggregateId = $aggregateId;
        $this->eventClass = get_class($event);
        $this->payload = $this->serializeEvent($event);
        $this->occurredAt =$event->occurredAt();
        $this->version = $version;
        $this->eventSchemaVersion = $event::schemaVersion();
    }

    public function aggregateId(): string { return $this->aggregateId; }
    public function eventClass(): string { return $this->eventClass; }
    public function payload(): array { return $this->payload; }
    public function eventSchemaVersion(): int { return $this->eventSchemaVersion; }
    public function occurredAt(): \DateTimeImmutable { return $this->occurredAt; }

    private function serializeEvent(DomainEvent $event): array
    {
        return $event->serialize();
    }
}
