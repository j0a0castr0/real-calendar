<?php

namespace App\RealCalendarContext\Application\Commands\ScheduleVisitCommand;

/**
 * @see ScheduleVisitCommandHandler
 */
final class ScheduleVisitCommand
{
    public function __construct(
        public readonly string $listingId,
        public readonly string $visitorName,
        public readonly string $visitorPhone,
        public readonly string $visitorEmail,
        public readonly \DateTimeImmutable $visitStartTime,
        public readonly \DateTimeImmutable $visitEndTime
    ) {}
}
