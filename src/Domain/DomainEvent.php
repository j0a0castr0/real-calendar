<?php

namespace App\Domain;

interface DomainEvent
{
    public function aggregateId(): AggregateId;
    public function occurredAt(): \DateTimeImmutable;
    public function eventType(): string;
    public function serialize(): array;
    public static function deserialize(array $serialized): DomainEvent;
}
