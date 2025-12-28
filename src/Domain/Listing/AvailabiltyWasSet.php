<?php

namespace App\Domain\Listing;

use App\Domain\AbstractDomainEvent;

final class AvailabiltyWasSet extends AbstractDomainEvent
{
    private \DateTimeImmutable $from;

    public function from(): \DateTimeImmutable
    {
        return $this->from;
    }

    public function to(): \DateTimeImmutable
    {
        return $this->to;
    }
    private \DateTimeImmutable $to;

    public function __construct(\DateTimeImmutable $from, \DateTimeImmutable $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function payload(): array
    {
        return [
            'aggregateId' => $this->aggregateId(),
            'from' => $this->from->format(\DateTimeImmutable::ATOM),
            'to' => $this->to->format(\DateTimeImmutable::ATOM)
        ];
    }
}
