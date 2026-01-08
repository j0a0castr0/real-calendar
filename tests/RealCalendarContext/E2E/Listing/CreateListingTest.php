<?php

namespace App\Tests\RealCalendarContext\E2E\Listing;

use App\Tests\Shared\E2ETestCase;
use PHPUnit\Framework\Attributes\Test;

final class CreateListingTest extends E2ETestCase
{
    #[Test]
    public function itShouldCreateListing() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(201, $statusCode);
    }

    #[Test]
    public function itShouldNotCreateListIfMissingPropertyReference() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'agent' => 'agent'
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(400, $statusCode);
    }

    #[Test]
    public function itShouldNotCreateListIfMissingAgent() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference',
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(400, $statusCode);
    }

    #[Test]
    public function itShouldNotCreateListIfEmptyAgent() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => '',
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(400, $statusCode);
    }

    #[Test]
    public function itShouldNotCreateListIfEmptyPropertyReference() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => '',
            'agent' => 'agent',
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(400, $statusCode);
    }
}
