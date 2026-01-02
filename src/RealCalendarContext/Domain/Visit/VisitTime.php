<?php

namespace App\RealCalendarContext\Domain\Visit;

final class VisitTime
{
    public function __construct(private \DateTimeImmutable $value) { }

    public static function startingAt(\DateTimeImmutable $value) : self
    {
        return new self($value);
    }

    public function serialize(): array
    {
        return [
            'startsAt' => $this->value->format('c'),
        ];
    }
}
