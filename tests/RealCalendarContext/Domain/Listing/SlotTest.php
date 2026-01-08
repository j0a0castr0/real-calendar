<?php

namespace App\Tests\RealCalendarContext\Domain\Listing;

use App\RealCalendarContext\Domain\Listing\Slot;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group("domain")]
class SlotTest extends TestCase
{
    #[Test]
    public function itShouldDetectOverlapingSlot() :  void
    {
        $slot = new Slot(
            (new \DateTimeImmutable())->modify('+1 hour'),
            (new \DateTimeImmutable())->modify('+2 hour')
        );

        $this->assertTrue(
            $slot->overlaps(
                new Slot(
                    (new \DateTimeImmutable())->modify('+30 minutes'),
                    (new \DateTimeImmutable())->modify('+1 hour 30 minutes')
                )
            )
        );
    }

    #[Test]
    public function itShouldDetectNonOverlapingSlot() :  void
    {
        $slot = new Slot(
            (new \DateTimeImmutable())->modify('+1 hour'),
            (new \DateTimeImmutable())->modify('+2 hour')
        );

        $this->assertFalse(
            $slot->overlaps(
                new Slot(
                    (new \DateTimeImmutable())->modify('+2 hour'),
                    (new \DateTimeImmutable())->modify('+3 hour')
                )
            )
        );
    }

    #[Test]
    public function itShouldBeEqualSlotsIfExactSameTimesTillTheMinute() :  void
    {
        $slot = new Slot(
            (new \DateTimeImmutable())->modify('+1 hour'),
            (new \DateTimeImmutable())->modify('+2 hour')
        );

        $slot2 = new Slot(
            (new \DateTimeImmutable())->modify('+1 hour'),
            (new \DateTimeImmutable())->modify('+2 hour')
        );

        $this->assertTrue($slot->equals($slot2));
    }

    #[Test]
    public function itShouldNotBeEqualSlotsIfDifferentMinutes() :  void
    {
        $slot = new Slot(
            (new \DateTimeImmutable())->modify('+1 hour'),
            (new \DateTimeImmutable())->modify('+2 hour')
        );

        $slot2 = new Slot(
            (new \DateTimeImmutable())->modify('+1 hour 1 minute'),
            (new \DateTimeImmutable())->modify('+2 hour')
        );

        $this->assertFalse($slot->equals($slot2));
    }

    #[Test]
    public function itShouldNotEqualSlotsIfOnlySecondsAreDifferent() :  void
    {
        $slot = new Slot(
            (new \DateTimeImmutable())->modify('+1 hour'),
            (new \DateTimeImmutable())->modify('+2 hour')
        );

        $slot2 = new Slot(
            (new \DateTimeImmutable())->modify('+1 hour 2 seconds'),
            (new \DateTimeImmutable())->modify('+2 hour')
        );

        $this->assertTrue($slot->equals($slot2));
    }
}
