<?php

namespace App\Domain\Listing;

final class PropertyReference
{
    public function __construct(private string $reference) {}

    public function __toString(): string
    {
        return $this->reference;
    }
}
