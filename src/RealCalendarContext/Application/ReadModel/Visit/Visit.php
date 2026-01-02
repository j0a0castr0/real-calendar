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
        public readonly \DateTimeImmutable $visitStartTime,
        public readonly \DateTimeImmutable $visitEndTime
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
            'visitStartTime' => $this->visitStartTime->format('c'),
            'visitEndTime' => $this->visitEndTime->format('c'),
        ];
    }
}
