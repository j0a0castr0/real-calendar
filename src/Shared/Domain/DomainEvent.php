<?php

namespace App\Shared\Domain;

interface DomainEvent
{
    public function aggregateId(): AggregateId;
    public function occurredAt(): \DateTimeImmutable;
    public function eventType(): string;
    public function serialize(): array;
    public static function deserialize(array $serialized): static;
    public static function schemaVersion(): int;
}
