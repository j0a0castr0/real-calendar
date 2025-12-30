<?php

namespace App\Domain\Listing;

final class Agent
{
    public function __construct(private string $agentId)
    {
    }

    public function __toString(): string
    {
        return $this->agentId;
    }
}
