<?php

namespace App\RealCalendarContext\Domain\Visit;

use App\Shared\Domain\AggregateId;

interface VisitRepository
{
    public function get(AggregateId $id): Visit;
    public function save(Visit $visit): void;
}
