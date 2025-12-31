<?php

namespace App\RealCalendarContext\Domain\Listing;

final class PropertyReference
{
    public function __construct(private string $reference)
    {
        if (empty($reference)) {
            throw new \InvalidArgumentException('Property reference cannot be empty');
        }
    }

    public function __toString(): string
    {
        return $this->reference;
    }
}
