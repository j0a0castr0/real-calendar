<?php

namespace App\Tests\WywaContext\Domain;

use App\Shared\Domain\AggregateId;
use App\WywaContext\Domain\Demand\Demand;
use App\WywaContext\Domain\Demand\DemandAcknowledged;
use App\WywaContext\Domain\Demand\DemandReceived;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DemandTest extends TestCase
{
    #[Test]
    public function itShouldReceiveDemand() : void
    {
        $demand = Demand::receive(new \DateTimeImmutable());

        $this->assertNotEmpty($events = $demand->popRecordedEvents());
        $this->assertInstanceOf(DemandReceived::class, $events[0]);
    }

    #[Test]
    public function itShouldNotBePossibleToAcknowledgeADemandThatItWasNotReceived() : void
    {
        $demand = Demand::reconstitute($id = new AggregateId(), []);

        $this->assertEmpty($demand->eventsForAcknowledgeDemand(new \DateTimeImmutable()));
    }

    #[Test]
    public function itShouldBePossibleToAcknowledgeDemand() : void
    {
        $history = [
            new DemandReceived($id = new AggregateId(), new \DateTimeImmutable())
        ];
        $aggregate = Demand::reconstitute($id, $history);

        $events = $aggregate->eventsForAcknowledgeDemand(new \DateTimeImmutable());
        $this->assertNotEmpty($events);
        $this->assertInstanceOf(DemandAcknowledged::class, $events[0]);

        $aggregate = $aggregate->withEvents($events);
        $this->assertNotEmpty($events = $aggregate->popRecordedEvents());
        $this->assertInstanceOf(DemandAcknowledged::class, $events[0]);
    }

    #[Test]
    public function isShouldNotBePossibleToAcknowledgeSameDemandTwice() : void
    {
        $history = [
            new DemandReceived($id = new AggregateId(), new \DateTimeImmutable()),
            new DemandAcknowledged($id, new \DateTimeImmutable())
        ];
        $aggregate = Demand::reconstitute($id, $history);

        $this->assertEmpty($aggregate->eventsForAcknowledgeDemand(new \DateTimeImmutable()));
    }
}
