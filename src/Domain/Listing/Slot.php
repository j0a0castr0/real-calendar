<?php

namespace App\Domain\Listing;

final class Slot
{
    public function __construct(
        private \DateTimeImmutable $from,
        private \DateTimeImmutable $to
    ) {
        $this->ensureDatesAreFromTheSameDay();
    }

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

    /**
     * @throws \InvalidArgumentException if start and end times are not on the same day
     */
    private function ensureDatesAreFromTheSameDay(): void
    {
        if ($this->from->format('Y-m-d') !== $this->to->format('Y-m-d')) {
            throw new \InvalidArgumentException('Slot start and end times must be on the same day.');
        }
    }
}
