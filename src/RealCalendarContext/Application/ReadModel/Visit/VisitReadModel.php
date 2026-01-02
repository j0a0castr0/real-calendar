<?php

namespace App\RealCalendarContext\Application\ReadModel\Visit;

interface VisitReadModel
{
    /**
     * @return Visit[]
     */
    public function getByAgent(string $agent): array;
}
