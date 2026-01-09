<?php

namespace App\Tests\WywaContext\Domain;

use App\WywaContext\Domain\CommitmentConfidence;
use App\WywaContext\Domain\CommitmentSnapshot;
use App\WywaContext\Domain\ShouldAwaySessionEnd;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShouldEndSessionAwayTest extends TestCase
{
    #[Test]
    public function itShouldEndAnAwaySessionWhenConfidenceIsHigh() : void
    {
        $policyDecision = new ShouldAwaySessionEnd();

        $this->assertTrue($policyDecision->decide(
            new CommitmentSnapshot(CommitmentConfidence::HIGH, new \DateTimeImmutable())
        ));
    }

    #[Test]
    public function itShouldEndAnAwaySessionWhenConfidenceIsMedium() : void
    {
        $policyDecision = new ShouldAwaySessionEnd();

        $this->assertTrue($policyDecision->decide(
            new CommitmentSnapshot(CommitmentConfidence::MEDIUM, new \DateTimeImmutable())
        ));
    }

    #[Test]
    public function itShouldEndAnAwaySessionWhenConfidenceLow() : void
    {
        $policyDecision = new ShouldAwaySessionEnd();

        $this->assertFalse($policyDecision->decide(
            new CommitmentSnapshot(CommitmentConfidence::LOW, new \DateTimeImmutable())
        ));
    }
}
