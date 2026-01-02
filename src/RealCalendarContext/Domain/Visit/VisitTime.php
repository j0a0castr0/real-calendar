<?php

namespace App\RealCalendarContext\Domain\Visit;

final class VisitTime
{
    public function __construct(private \DateTimeImmutable $start, private \DateTimeImmutable $end) { }

    public static function between(\DateTimeImmutable $start, \DateTimeImmutable $end) : self
    {
        return new self($start, $end);
    }

    public static function deserialize(array $serialized) : self
    {
        return new self(
            \DateTimeImmutable::createFromFormat('c', $serialized['starts_at']),
            \DateTimeImmutable::createFromFormat('c', $serialized['ends_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'starts_at' => $this->start->format('c'),
            'ends_at' => $this->end->format('c')
        ];
    }

    public function start(): \DateTimeImmutable
    {
        return $this->start;
    }

    public function end(): \DateTimeImmutable
    {
        return $this->start;
    }
}
