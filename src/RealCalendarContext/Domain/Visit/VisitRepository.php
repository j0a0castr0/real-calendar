<?php

namespace App\RealCalendarContext\Domain\Visit;

use App\RealCalendarContext\Domain\AggregateId;

interface VisitRepository
{
    public function get(AggregateId $id): Visit;
    public function save(Visit $listing): void;
}
