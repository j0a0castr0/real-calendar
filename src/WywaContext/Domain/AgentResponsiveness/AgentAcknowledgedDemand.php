<?php

namespace App\WywaContext\Domain\AgentResponsiveness;

use App\Shared\Domain\AbstractDomainEvent;
use App\Shared\Domain\AggregateId;
use App\WywaContext\Domain\Shared\DemandId;

final class AgentAcknowledgedDemand extends AbstractDomainEvent
{
    public function __construct(
        AggregateId $id,
        private readonly string $demandId,
        \DateTimeImmutable $at
    ) {
        parent::__construct($id, $at);
    }

    public function demandId(): DemandId { return new DemandId($this->demandId); }

    public function serialize(): array
    {
        // TODO: Implement serialize() method.
    }

    public static function deserialize(array $serialized): static
    {
        // TODO: Implement deserialize() method.
    }
}
