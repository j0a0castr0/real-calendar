<?php

namespace App\Tests\WywaContext\Domain;

use App\WywaContext\Domain\Demand\Demand;
use App\WywaContext\Domain\Demand\DemandReceived;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RecordingDemandTest extends TestCase
{
    #[Test]
    public function itShouldReceiveDemand() : void
    {
        $demand = Demand::receive(new \DateTimeImmutable());

        $this->assertNotEmpty($events = $demand->popRecordedEvents());
        $this->assertInstanceOf(DemandReceived::class, $events[0]);
    }
}
