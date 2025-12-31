<?php

namespace App\RealCalendarContext\Application\Commands\CreateListingCommand;

/**
 * @see CreateListingCommandHandler
 */
final class CreateListingCommand
{
    public function __construct(
        public readonly string $propertyReference,
        public readonly string $agent,
    ) {}
}
