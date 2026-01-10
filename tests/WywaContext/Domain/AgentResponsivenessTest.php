<?php

namespace App\Tests\WywaContext\Domain;

use App\Shared\Domain\AggregateId;
use App\WywaContext\Domain\AgentResponsiveness\AgentResponsiveness;
use App\WywaContext\Domain\AgentResponsiveness\AwayPeriodEnded;
use App\WywaContext\Domain\AgentResponsiveness\AwayPeriodStarted;
use App\WywaContext\Domain\AgentResponsiveness\Confidence;
use App\WywaContext\Domain\AgentResponsiveness\DemandAcknowledged;
use App\WywaContext\Domain\Shared\DemandId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AgentResponsivenessTest extends TestCase
{
    #[Test]
    public function itShouldAcknowledgeDemandForAgentResponsiveness(): void
    {
        $history = [];

        $aggregate = AgentResponsiveness::reconstitute(new AggregateId(), $history);

        $events = $aggregate->eventsForDemandAcknowledged(new DemandId('demand-id'), new \DateTimeImmutable());
        $this->assertNotEmpty($events);
        $this->assertInstanceOf(DemandAcknowledged::class, $events[0]);

        $aggregate = $aggregate->withEvents([
            ...$events
        ]);

        $this->assertNotEmpty($recordedEvents = $aggregate->popRecordedEvents());
        $this->assertInstanceOf(DemandAcknowledged::class, $recordedEvents[0]);
    }

    #[Test]
    public function itShouldNotAcknowledgeDemandForAgentResponsivenessIfAlreadyAcknowledged(): void
    {
        $history = [
            new DemandAcknowledged($id = new AggregateId(), new DemandId('demand-id'), new \DateTimeImmutable())
        ];
        $aggregate = AgentResponsiveness::reconstitute($id, $history);

        $this->assertEmpty($aggregate->eventsForDemandAcknowledged(new DemandId('demand-id'), new \DateTimeImmutable()));
    }

    #[Test]
    public function itShouldComputeConfidenceHighAfterDemandWasAcknowledged(): void
    {
        $aggregate = AgentResponsiveness::reconstitute($id = new AggregateId(), [
            new DemandAcknowledged($id, new DemandId('demand-id'), new \DateTimeImmutable())
        ]);

        $this->assertEquals(Confidence::HIGH, $aggregate->confidenceAt(new \DateTimeImmutable()));
    }

    #[Test]
    public function itShouldComputeConfidenceLowIfNoDemandWasAcknowledged(): void
    {
        $aggregate = AgentResponsiveness::reconstitute($id = new AggregateId(), []);

        $this->assertEquals(Confidence::LOW, $aggregate->confidenceAt(new \DateTimeImmutable()));
    }

    #[Test]
    public function itShouldStartAwayPeriod(): void
    {
        $aggregate = AgentResponsiveness::reconstitute($id = new AggregateId(), [
            new DemandAcknowledged($id, new DemandId('demand-id'), new \DateTimeImmutable())
        ]);

        $events = $aggregate->eventsForStartAwayPeriod(new \DateTimeImmutable());
        $this->assertNotEmpty($events);
        $this->assertInstanceOf(AwayPeriodStarted::class, $events[0]);

        $aggregate = $aggregate->withEvents([
            ...$events
        ]);

        $this->assertNotEmpty($events = $aggregate->popRecordedEvents());
        $this->assertInstanceOf(AwayPeriodStarted::class, $events[0]);
    }

    #[Test]
    public function itShouldComputeConfidenceLowIfIfAwayWindowStartedAfterAcknowledgingDemand(): void
    {
        $aggregate = AgentResponsiveness::reconstitute($id = new AggregateId(), [
            new DemandAcknowledged($id, new DemandId('demand-id'), new \DateTimeImmutable())
        ]);

        $aggregate = $aggregate->withEvents([
            ...$aggregate->eventsForStartAwayPeriod(new \DateTimeImmutable())
        ]);

        $this->assertEquals(Confidence::LOW, $aggregate->confidenceAt(new \DateTimeImmutable()));
    }

    #[Test]
    public function itShouldEndAwayPeriod(): void
    {
        $aggregate = AgentResponsiveness::reconstitute($id = new AggregateId(), [
            new DemandAcknowledged($id, new DemandId('demand-id'), new \DateTimeImmutable()),
            new AwayPeriodStarted($id, new \DateTimeImmutable())
        ]);

        $events = $aggregate->eventsForEndAwayPeriod(new \DateTimeImmutable());
        $this->assertNotEmpty($events);
        $this->assertInstanceOf(AwayPeriodEnded::class, $events[0]);

        $aggregate = $aggregate->withEvents([
            ...$events
        ]);

        $this->assertNotEmpty($events = $aggregate->popRecordedEvents());
        $this->assertInstanceOf(AwayPeriodEnded::class, $events[0]);
    }

    #[Test]
    public function itShouldComputeConfidenceMediumIfAwayWindowJustEnded(): void
    {
        $aggregate = AgentResponsiveness::reconstitute($id = new AggregateId(), [
            new DemandAcknowledged($id, new DemandId('demand-id'), new \DateTimeImmutable()),
            new AwayPeriodStarted($id, new \DateTimeImmutable()),
            new AwayPeriodEnded($id, new \DateTimeImmutable())
        ]);

        $this->assertEquals(Confidence::MEDIUM, $aggregate->confidenceAt(new \DateTimeImmutable()));
    }

    #[Test]
    public function itShouldComputeConfidenceHighIfAwayWindowJustEndedAndAgentAcknowledgedDemand(): void
    {
        $t0 = new \DateTimeImmutable('2026-01-10 10:00:00');
        $t1 = new \DateTimeImmutable('2026-01-10 10:01:00');
        $t2 = new \DateTimeImmutable('2026-01-10 10:02:00');
        $t3 = new \DateTimeImmutable('2026-01-10 10:03:00');

        $aggregate = AgentResponsiveness::reconstitute($id = new AggregateId(), [
            new DemandAcknowledged($id, new DemandId('demand-id'), $t0),
            new AwayPeriodStarted($id, $t1),
            new AwayPeriodEnded($id, $t2),
            new DemandAcknowledged($id, new DemandId('demand-id2'), $t3)
        ]);

        $this->assertEquals(Confidence::HIGH, $aggregate->confidenceAt($t3));
    }
}
