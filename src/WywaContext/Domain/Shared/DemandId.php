<?php

namespace App\WywaContext\Domain\Shared;

final class DemandId
{
    public function __construct(private readonly string $demandId)
    { }

    public function __toString(): string
    {
        return $this->demandId;
    }
}
