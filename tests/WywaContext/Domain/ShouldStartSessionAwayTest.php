<?php

namespace App\Tests\WywaContext\Domain;

use App\WywaContext\Domain\AgentResponsiveness\Confidence;
use App\WywaContext\Domain\AgentResponsiveness\ResponsivenessSnapshot;
use App\WywaContext\Domain\AwaySession\ShouldStartSessionStart;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ShouldStartSessionAwayTest extends TestCase
{
    #[Test]
    public function itShouldDecideToStartSessionAwayWhenConfidenceIsLow() : void
    {
        $policy = new ShouldStartSessionStart();
        $snapshot = new ResponsivenessSnapshot(Confidence::LOW, new \DateTimeImmutable());

        $this->assertTrue($policy->decide($snapshot));
    }

    #[Test]
    public function itShouldDecideToDontStartSessionAwayWhenConfidenceIsHigh() : void
    {
        $policy = new ShouldStartSessionStart();
        $snapshot = new ResponsivenessSnapshot(Confidence::HIGH, new \DateTimeImmutable());

        $this->assertFalse($policy->decide($snapshot));
    }

    #[Test]
    public function itShouldDecideToDontStartSessionAwayWhenConfidenceIsMedium() : void
    {
        $policy = new ShouldStartSessionStart();
        $snapshot = new ResponsivenessSnapshot(Confidence::MEDIUM, new \DateTimeImmutable());

        $this->assertFalse($policy->decide($snapshot));
    }
}
