<?php

namespace App\RealCalendarContext\Application\ReadModel\Visit;

final class Visit
{
    public function __construct(
        public readonly string $id,
        public readonly string $listingId,
        public readonly string $agent,
        public readonly string $visitorName,
        public readonly string $visitorEmail,
        public readonly ?string $visitorPhone,
        public readonly \DateTimeImmutable $visitTime,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'listingId' => $this->listingId,
            'agent' => $this->agent,
            'visitorName' => $this->visitorName,
            'visitorEmail' => $this->visitorEmail,
            'visitorPhone' => $this->visitorPhone,
            'visitTime' => $this->visitTime->format('c'),
        ];
    }
}
