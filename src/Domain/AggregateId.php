<?php

namespace App\Domain;

use Ramsey\Uuid\Uuid;

final class AggregateId
{
    private string $uuid;

    public function __construct(?string $uuid = null)
    {
        $uuid = $uuid ?? Uuid::uuid4()->toString();

        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("Invalid UUID: $uuid");
        }

        $this->uuid = $uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $other): bool
    {
        return $this->uuid === (string)$other;
    }
}
