<?php

namespace App\WywaContext\Domain\AwaySession;

use App\Shared\Domain\AbstractDomainEvent;
use App\Shared\Domain\AggregateId;

final class DemandCaptured extends AbstractDomainEvent
{
    public function __construct(
        private readonly AggregateId $id,
        private readonly string $demandId, $leadReference, $capturedAt)
    {
    }

    public function serialize(): array
    {
        // TODO: Implement serialize() method.
    }

    public static function deserialize(array $serialized): static
    {
        // TODO: Implement deserialize() method.
    }
}
