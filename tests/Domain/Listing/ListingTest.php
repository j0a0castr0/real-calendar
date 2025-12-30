<?php

namespace App\Tests\Domain\Listing;

use App\Domain\Listing\Agent;
use App\Domain\Listing\AvailabilitySlotWasAdded;
use App\Domain\Listing\CouldNotAddAvailabilityToListing;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingWasCreated;
use App\Domain\Listing\PropertyReference;
use App\Domain\Listing\Slot;
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
                (new \DateTimeImmutable())->modify('+1 hour'),
                (new \DateTimeImmutable())->modify('+2 hour')
            )
        );
        $events = $listing->popRecordedEvents();

        $this->assertNotEmpty($events);
        $this->assertInstanceOf(AvailabilitySlotWasAdded::class, $events[0]);
    }

    #[Test]
    public function itShouldNotOverlapAvailability() : void
    {
        $listing = Listing::create();


        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable())->modify('+1 hour'),
                (new \DateTimeImmutable())->modify('+2 hour')
            )
        );

        $this->expectException(CouldNotAddAvailabilityToListing::class);
        $listing->addAvailabilitySlot(
            new Slot(
                (new \DateTimeImmutable())->modify('+30 minutes'),
                (new \DateTimeImmutable())->modify('+1 hour 30 minutes')
            )
        );

    }
}
