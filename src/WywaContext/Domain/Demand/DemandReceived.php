<?php

namespace App\WywaContext\Domain\Demand;

use App\Shared\Domain\AbstractDomainEvent;

final class DemandReceived extends AbstractDomainEvent
{
    public function serialize(): array
    {
        // TODO: Implement serialize() method.
    }

    public static function deserialize(array $serialized): static
    {
        // TODO: Implement deserialize() method.
    }
}
