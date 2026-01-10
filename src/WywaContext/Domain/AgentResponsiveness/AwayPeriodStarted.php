<?php

namespace App\WywaContext\Domain\AgentResponsiveness;

use App\Shared\Domain\AbstractDomainEvent;

final class AwayPeriodStarted extends AbstractDomainEvent
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
