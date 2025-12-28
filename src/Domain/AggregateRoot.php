<?php

namespace App\Domain;

abstract class AggregateRoot
{
    protected AggregateId $id;

    /** @var DomainEvent[] */
    private array $recordedEvents = [];

    protected int $version = 0;

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function version(): int
    {
        return $this->version;
    }

    /** Reconstitution from history */
    public static function reconstitute(AggregateId $id, iterable $events): static
    {
        $instance = new static();
        $instance->id = $id;

        foreach ($events as $event) {
            $instance->applyEvent($event);
        }

        return $instance;
    }

    /** Record new event */
    protected function raise(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
        $this->applyEvent($event);
    }

    /** @return DomainEvent[] */
    public function popRecordedEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }

    /** Core dispatching logic */
    private function applyEvent(DomainEvent $event): void
    {
        $shortName = (new \ReflectionClass($event))->getShortName();
        $method = 'apply' . $shortName;

        if (!method_exists($this, $method)) {
            throw new \LogicException(
                sprintf(
                    'Missing %s::%s() for event %s',
                    static::class,
                    $method,
                    $event::class
                )
            );
        }

        $this->$method($event);
        $this->version++;
    }
}
