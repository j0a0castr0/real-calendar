<?php

namespace App\Tests\WywaContext\Domain;

use App\Shared\Domain\AggregateId;
use App\WywaContext\Domain\AwaySession;
use App\WywaContext\Domain\AwaySessionEnded;
use App\WywaContext\Domain\AwaySessionStarted;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AwaySessionEndTest extends TestCase
{
    #[Test]
    public function itShouldBePossibleToEndStartedAwaySession() : void
    {
        $history = [new AwaySessionStarted($id = new AggregateId(), new \DateTimeImmutable())];
        $awaySession = AwaySession::reconstitute($id, $history);

        $events = $awaySession->eventsForEnd(new \DateTimeImmutable());

        $this->assertCount(1, $events);
        $this->assertInstanceOf(AwaySessionEnded::class, $events[0]);
    }

    #[Test]
    public function itShouldBePossibleToCommitToEndStartedAwaySession() : void
    {
        $history = [new AwaySessionStarted($id = new AggregateId(), new \DateTimeImmutable())];
        $awaySession = AwaySession::reconstitute($id, $history);

        $awaySession = $awaySession->withEvents(
            $awaySession->eventsForEnd(new \DateTimeImmutable())
        );

        $this->assertCount(1, $events = $awaySession->popRecordedEvents());
        $this->assertInstanceOf(AwaySessionEnded::class, $events[0]);
    }

    #[Test]
    public function itShouldNotBePossibleToEndAndAwaySessionEnded() : void
    {
        $history = [
            new AwaySessionStarted($id = new AggregateId(), new \DateTimeImmutable()),
            new AwaySessionEnded($id, new \DateTimeImmutable())
        ];
        $awaySession = AwaySession::reconstitute($id, $history);

        $this->assertEmpty($awaySession->eventsForEnd(new \DateTimeImmutable()));
    }
}
