<?php

namespace App\Application\Commands\AddAvailabilitySlotCommand;

/**
 * @see AddAvailabilitySlotCommandHandler
 */
final class AddAvailabilitySlotCommand
{
    public function __construct(
        public readonly string $listingId,
        public readonly \DateTimeImmutable $from,
        public readonly \DateTimeImmutable $to
    ) {}
}
