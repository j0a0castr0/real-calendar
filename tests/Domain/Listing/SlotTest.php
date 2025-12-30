<?php

namespace App\Tests\Domain\Listing;

use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingWasCreated;
use App\Domain\Listing\Slot;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

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
}
