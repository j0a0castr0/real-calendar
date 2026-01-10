<?php

namespace App\WywaContext\Domain\AwaySession;

use App\Shared\Domain\AbstractDomainEvent;
use App\Shared\Domain\AggregateId;
use App\WywaContext\Domain\Shared\DemandId;

final class DemandCaptured extends AbstractDomainEvent
{
    public function __construct(
        private readonly AggregateId $id,
        private readonly string $demandId,
        private readonly \DateTimeImmutable $capturedAt
    ) {
        parent::__construct($id, $capturedAt);
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
