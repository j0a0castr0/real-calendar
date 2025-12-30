<?php

namespace App\Domain\Listing;

final class Slot
{
    public function __construct(
        private \DateTimeImmutable $from,
        private \DateTimeImmutable $to
    ) {}

    public function from(): \DateTimeImmutable
    {
        return $this->from;
    }

    public function to(): \DateTimeImmutable
    {
        return $this->to;
    }

    public function overlaps(Slot $other): bool
    {
        return $this->from < $other->to && $other->from < $this->to;
    }
}
