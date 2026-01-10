<?php

namespace App\Tests\WywaContext\Domain;

use App\WywaContext\Domain\AgentResponsiveness\Confidence;
use App\WywaContext\Domain\AgentResponsiveness\ResponsivenessSnapshot;
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
            new ResponsivenessSnapshot(Confidence::HIGH, new \DateTimeImmutable())
        ));
    }

    #[Test]
    public function itShouldEndAnAwaySessionWhenConfidenceIsMedium() : void
    {
        $policyDecision = new ShouldAwaySessionEnd();

        $this->assertTrue($policyDecision->decide(
            new ResponsivenessSnapshot(Confidence::MEDIUM, new \DateTimeImmutable())
        ));
    }

    #[Test]
    public function itShouldEndAnAwaySessionWhenConfidenceLow() : void
    {
        $policyDecision = new ShouldAwaySessionEnd();

        $this->assertFalse($policyDecision->decide(
            new ResponsivenessSnapshot(Confidence::LOW, new \DateTimeImmutable())
        ));
    }
}
