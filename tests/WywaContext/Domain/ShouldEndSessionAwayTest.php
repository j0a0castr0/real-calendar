<?php

namespace App\Tests\WywaContext\Domain;

use App\WywaContext\Domain\AwaySession\ResponsivenessConfidence;
use App\WywaContext\Domain\AwaySession\ResponsivenessSnapshot;
use App\WywaContext\Domain\AwaySession\ShouldAwaySessionEnd;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShouldEndSessionAwayTest extends TestCase
{
    #[Test]
    public function itShouldEndAnAwaySessionWhenConfidenceIsHigh() : void
    {
        $policyDecision = new ShouldAwaySessionEnd();

        $this->assertTrue($policyDecision->decide(
            new ResponsivenessSnapshot(ResponsivenessConfidence::HIGH, new \DateTimeImmutable())
        ));
    }

    #[Test]
    public function itShouldEndAnAwaySessionWhenConfidenceIsMedium() : void
    {
        $policyDecision = new ShouldAwaySessionEnd();

        $this->assertTrue($policyDecision->decide(
            new ResponsivenessSnapshot(ResponsivenessConfidence::MEDIUM, new \DateTimeImmutable())
        ));
    }

    #[Test]
    public function itShouldEndAnAwaySessionWhenConfidenceLow() : void
    {
        $policyDecision = new ShouldAwaySessionEnd();

        $this->assertFalse($policyDecision->decide(
            new ResponsivenessSnapshot(ResponsivenessConfidence::LOW, new \DateTimeImmutable())
        ));
    }
}
