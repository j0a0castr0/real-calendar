<?php

namespace App\RealCalendarContext\Application\Queries\GetVisitsForAgent;

/**
 * @see GetVisitsForAgentQueryHandler
 */
final class GetVisitsForAgentQuery
{
    public function __construct(public readonly string $agent) {}
}
