<?php

namespace App\Tests\WywaContext\Domain;

use App\Shared\Domain\AggregateId;
use App\WywaContext\Domain\AwaySession\AwaySession;
use App\WywaContext\Domain\AwaySession\AwaySessionStarted;
use App\WywaContext\Domain\AwaySession\DemandCaptured;
use App\WywaContext\Domain\Shared\DemandId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AwaySessionTest extends TestCase
{
    #[Test]
    public function itShouldNotCaptureDemandIfTheAwaySessionDidNotStart() : void
    {
        $id = new AggregateId();
        $agg = AwaySession::reconstitute($id, []);

        $events = $agg->eventsForCaptureDemand(new DemandId('demand-id'), new \DateTimeImmutable());

        self::assertEmpty($events);
    }

    #[Test]
    public function itShouldCaptureDemandForaAwaySessionStarted() : void
    {
        $id = new AggregateId();
        $history = [
            new AwaySessionStarted($id, new \DateTimeImmutable())
        ];
        $agg = AwaySession::reconstitute($id, $history);

        $events = $agg->eventsForCaptureDemand(new DemandId('demand-id'),  new \DateTimeImmutable());
        self::assertCount(1, $events);
        self::assertInstanceOf(DemandCaptured::class, $events[0]);

        $agg = $agg->withEvents($events);

        $recordedEvents = $agg->popRecordedEvents();
        self::assertCount(1, $recordedEvents);
        self::assertInstanceOf(DemandCaptured::class, $recordedEvents[0]);
    }

    #[Test]
    public function itShouldNotCaptureTwiceSameDemandForSameAwaySession() : void
    {
        $id = new AggregateId();
        $agg = AwaySession::reconstitute($id, [
            new AwaySessionStarted($id, new \DateTimeImmutable()),
        ]);

        $events1 = $agg->eventsForCaptureDemand(new DemandId('demand-id'), new \DateTimeImmutable());
        $agg = $agg->withEvents($events1);
        self::assertCount(1, $agg->popRecordedEvents());

        $events2 = $agg->eventsForCaptureDemand(new DemandId('demand-id'), new \DateTimeImmutable());
        $agg = $agg->withEvents($events2);
        self::assertCount(0, $agg->popRecordedEvents());
    }
}
