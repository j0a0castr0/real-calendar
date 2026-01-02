<?php

namespace App\RealCalendarContext\Domain\Visit;

use App\RealCalendarContext\Domain\AggregateId;
use App\RealCalendarContext\Domain\AggregateRoot;

class Visit extends AggregateRoot
{
    private ListingId $listingId;
    private Visitor $visitor;
    private VisitTime $time;

    private function __construct()
    {
    }

    public static function schedule(
        ListingId $listingId,
        Visitor $visitor,
        VisitTime $visitTime,
        ?AggregateId $id = null
    ): Visit {
        $visit = new self();
        $visit->raise(
            new VisitWasScheduled(
                $id ?? new AggregateId(),
                $listingId,
                $visitor,
                $visitTime
        ));

        return $visit;
    }

    public function applyVisitWasScheduled(VisitWasScheduled $event)
    {
        $this->id = $event->aggregateId();
        $this->listingId = $event->listingId();
        $this->visitor = $event->visitor();
        $this->time = $event->visitTime();
    }
}
