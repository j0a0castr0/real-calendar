<?php

namespace App\RealCalendarContext\Domain\Visit;

final class Name
{
    public function __construct(
        private string $value
    ) {
        if (empty($value))
            throw new \InvalidArgumentException('Name cannot be empty');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
