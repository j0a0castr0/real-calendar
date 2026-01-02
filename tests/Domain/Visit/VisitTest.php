<?php

namespace App\Tests\Domain\Visit;

use App\RealCalendarContext\Domain\Visit\ListingId;
use App\RealCalendarContext\Domain\Visit\Visit;
use App\RealCalendarContext\Domain\Visit\Visitor;
use App\RealCalendarContext\Domain\Visit\VisitTime;
use App\RealCalendarContext\Domain\Visit\VisitWasScheduled;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group("domain")]
class VisitTest extends TestCase
{
    #[Test]
    public function itShouldCreateAVisit() : void
    {
        $visit = Visit::schedule(
            new ListingId("some-listing"),
            Visitor::createFromValues("John Doe", "1234567890", "john.doe@example.com"),
            VisitTime::between(new \DateTimeImmutable('2023-01-01'))
        );

        $events = $visit->popRecordedEvents();

        $this->assertNotEmpty($events);
        $this->assertInstanceOf(VisitWasScheduled::class, $events[0]);
    }
}
