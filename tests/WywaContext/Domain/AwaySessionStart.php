<?php

namespace App\Tests\WywaContext\Domain;

use App\WywaContext\Domain\AwaySession\AwaySession;
use App\WywaContext\Domain\AwaySession\AwaySessionStarted;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AwaySessionStart extends TestCase
{
    #[Test]
    public function itShouldStartAwaySession() : void
    {
        $this->assertNotEmpty(
            $events = AwaySession::start(new \DateTimeImmutable())->popRecordedEvents()
        );
        $this->assertInstanceof(AwaySessionStarted::class, $events[0]);
    }
}
