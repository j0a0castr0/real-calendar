<?php

namespace App\Tests\Domain\Listing;

use App\RealCalendarContext\Domain\Listing\Agent;
use App\RealCalendarContext\Domain\Listing\AvailabilitySlotWasAdded;
use App\RealCalendarContext\Domain\Listing\CouldNotAddAvailabilityToListing;
use App\RealCalendarContext\Domain\Listing\Listing;
use App\RealCalendarContext\Domain\Listing\ListingWasCreated;
use App\RealCalendarContext\Domain\Listing\PropertyReference;
use App\RealCalendarContext\Domain\Listing\Slot;
use App\RealCalendarContext\Domain\Listing\SlotWasRemoved;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group("domain")]
class ListingTest extends TestCase
{
    #[Test]
    public function itShouldCreateAListing() :  void
    {
        $listing = Listing::create(
            new PropertyReference("some-reference"),
            new Agent("some-agent"),
        );

        $events = $listing->popRecordedEvents();

        $this->assertNotEmpty($events);
        $this->assertInstanceOf(ListingWasCreated::class, $events[0]);
    }

    #[Test]
    public function itShouldBePossibleToSetAvailabilityForListing() : void
    {
        $listing = Listing::create(
            new PropertyReference("some-reference"),
            new Agent("some-agent"),
        );
        $listing->popRecordedEvents();

        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+1 hour'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+2 hour')
            )
        );
        $events = $listing->popRecordedEvents();

        $this->assertNotEmpty($events);
        $this->assertInstanceOf(AvailabilitySlotWasAdded::class, $events[0]);
    }

    #[Test]
    public function itShouldNotOverlapAvailability() : void
    {
        $listing = Listing::create(
            new PropertyReference("some-reference"),
            new Agent("some-agent"),
        );


        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+1 hour'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+2 hour')
            )
        );

        $this->expectException(CouldNotAddAvailabilityToListing::class);
        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+30 minutes'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+1 hour 30 minutes')
            )
        );

    }

    #[Test]
    public function itShouldNotBePossibleToCreateListingWithEmptyAgent() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        Listing::create(
            new PropertyReference("some-reference"),
            new Agent(""),
        );
    }

    #[Test]
    public function itShouldNotBePossibleToCreateListingWithEmptyPropertyReference() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        Listing::create(
            new PropertyReference(""),
            new Agent("agent"),
        );
    }

    #[Test]
    public function itShouldNotBePossibleToBookASlotForDifferentDays() : void
    {
        $listing = Listing::create(
            new PropertyReference("some-reference"),
            new Agent("some-agent"),
        );

        $this->expectException(\InvalidArgumentException::class);
        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+30 minutes'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+24 hour 30 minutes')
            )
        );
    }

    #[Test]
    public function itShouldRemoveTheSlateForThisDate() : void
    {
        $listing = Listing::create(
            new PropertyReference("some-reference"),
            new Agent("some-agent"),
        );
        $listing->addAvailabilitySlot(
            new Slot(
                $startTime = (new \DateTimeImmutable('2025-12-24'))->modify('+1 hour'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+2 hour')
            )
        );
        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+3 hour'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+4 hour')
            )
        );
        $listing->popRecordedEvents();

        $listing->removeSlot(
            $startTime
        );

        $events = $listing->popRecordedEvents();
        $this->assertNotEmpty($events);
        $this->assertInstanceOf(SlotWasRemoved::class, $events[0]);
    }

    #[Test]
    public function itShouldDoNothingWhenRemovingASlotItDoesntExist() : void
    {
        $listing = Listing::create(
            new PropertyReference("some-reference"),
            new Agent("some-agent"),
        );
        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+1 hour'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+2 hour')
            )
        );
        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+3 hour'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+4 hour')
            )
        );
        $listing->popRecordedEvents();

        $listing->removeSlot(
            (new \DateTimeImmutable('2025-12-24'))->modify('+5 hour'),
        );

        $events = $listing->popRecordedEvents();
        $this->assertEmpty($events);
    }

    #[Test]
    public function itSholdBePossibleToAddConsecutiveSlots() : void
    {
        $listing = Listing::create(
            new PropertyReference("some-reference"),
            new Agent("some-agent"),
        );


        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+1 hour'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+2 hour')
            )
        );

        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable('2025-12-24'))->modify('+2 hour'),
                (new \DateTimeImmutable('2025-12-24'))->modify('+3 hour')
            )
        );

        $this->assertTrue(true);
        $this->assertCount(3, $listing->popRecordedEvents());
    }
}
