<?php

namespace App\Application\ReadModel\Listing;

final readonly class Listing
{
    public function __construct(
        private string $id,
        private string $propertyReference,
        private string $agent
    ){}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'propertyReference' => $this->propertyReference,
            'agent' => $this->agent,
        ];
    }
}
