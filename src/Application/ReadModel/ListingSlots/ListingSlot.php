<?php

namespace App\Application\ReadModel\ListingSlots;

class ListingSlot
{
    public function __construct(
        public \DateTimeImmutable $from,
        public \DateTimeImmutable $to,
    ) {}

    public function toArray(): array
    {
        return [
            'from' => $this->from->format('c'),
            'to' => $this->to->format('c'),
        ];
    }
}
