<?php

namespace App\Domain;

abstract class AggregateRoot
{
    protected string $id;

    /** @var DomainEvent[] */
    private array $recordedEvents = [];

    public function id(): string
    {
        return $this->id;
    }

    /** Reconstitution from history */
    public static function reconstitute(string $id, iterable $events): static
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
    }
}
