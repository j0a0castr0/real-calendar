<?php

namespace App\Domain\Listing;

final class Agent
{
    public function __construct(private string $agentId)
    {
        if (empty($agentId)) {
            throw new \InvalidArgumentException('Agent ID cannot be empty');
        }
    }

    public function __toString(): string
    {
        return $this->agentId;
    }
}
