<?php

namespace App\Tests\Domain;

use App\Domain\Listing\AvailabiltyWasSet;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingWasCreated;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

#[Group("domain")]
class ListingTest extends TestCase
{
    #[Test]
    public function itShouldCreateAListing() :  void
    {
        $listing = Listing::create(id: Uuid::uuid4());

        $events = $listing->popRecordedEvents();

        $this->assertNotEmpty($events);
        $this->assertInstanceOf(ListingWasCreated::class, $events[0]);
    }

    #[Test]
    public function itShouldBePossibleToSetAvailabilityForListing() : void
    {
        $listing = Listing::create(id: Uuid::uuid4());
        $listing->popRecordedEvents();

        $listing->addAvailability(new \DateTimeImmutable(), new \DateTimeImmutable());
        $events = $listing->popRecordedEvents();

        $this->assertNotEmpty($events);
        $this->assertInstanceOf(AvailabiltyWasSet::class, $events[0]);
    }

    #[Test]
    public function itShouldNotBePossibleToHaveDuplicateAvailabilities() : void
    {
        $listing = Listing::create(id: Uuid::uuid4());
        $listing->popRecordedEvents();

        $from = new \DateTimeImmutable();
        $to = new \DateTimeImmutable();

        $listing->addAvailability($from, $to);
        $listing->addAvailability($from, $to);
        $events = $listing->popRecordedEvents();

        $this->assertCount(1, $events);
    }
}
