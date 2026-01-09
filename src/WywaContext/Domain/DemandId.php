<?php

namespace App\WywaContext\Domain;

final class DemandId
{
    public function __construct(private readonly string $demandId)
    { }

    public function __toString(): string
    {
        return $this->demandId;
    }
}
