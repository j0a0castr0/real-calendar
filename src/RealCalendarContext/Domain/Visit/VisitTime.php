<?php

namespace App\RealCalendarContext\Domain\Visit;

final class VisitTime
{
    public function __construct(private \DateTimeImmutable $value) { }

    public static function startingAt(\DateTimeImmutable $value) : self
    {
        return new self($value);
    }

    public static function deserialize(array $serialized) : self
    {
        return new self(
            \DateTimeImmutable::createFromFormat('c', $serialized['starts_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'starts_at' => $this->value->format('c'),
        ];
    }

    public function value(): \DateTimeImmutable
    {
        return $this->value;
    }
}
