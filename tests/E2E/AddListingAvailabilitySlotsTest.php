<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\Attributes\Test;

class AddListingAvailabilitySlotsTest extends E2ETestCase
{
    #[Test]
    public function itShouldNotBePossibleToAddSlotsToANonExistingListing() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing/bleh/availability', [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => new \DateTimeImmutable('+1 hours')->format('c')
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(400, $statusCode);
    }

    #[Test]
    public function itShouldNotBePossibleToAddSlotsToANonExistingListingWithValidId() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing/f828d9f5-71eb-4409-ba80-5eb6a7f8a5af/availability', [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => new \DateTimeImmutable('+1 hours')->format('c')
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(404, $statusCode);
    }

    #[Test]
    public function itShouldBePossibleToAddAnAvailabilitySlot() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);
        $response = $this->getJsonResponse();
        $id = $response['data']['id'];

        $this->jsonRequest('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => new \DateTimeImmutable('+1 hours')->format('c')
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(200, $statusCode);
    }

    #[Test]
    public function itShouldNotBePossibleToOverlapSlots() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);
        $response = $this->getJsonResponse();
        $id = $response['data']['id'];

        $this->jsonRequestWithAuth('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => new \DateTimeImmutable('+1 hours')->format('c')
        ]);
        $this->jsonRequestWithAuth('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+1 hours 30 mins')->format('c'),
            'from' => new \DateTimeImmutable('30 mins')->format('c')
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(400, $statusCode);
    }
}
