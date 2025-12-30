<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\Attributes\Test;

final class CreateListingTest extends E2ETestCase
{
    #[Test]
    public function itShouldCreateListing() : void
    {
        $this->jsonRequest('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(201, $statusCode);
    }

    #[Test]
    public function itShouldNotCreateListIfMissingPropertyReference() : void
    {
        $this->jsonRequest('POST', '/listing', [
            'agent' => 'agent'
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(400, $statusCode);
    }

    #[Test]
    public function itShouldNotCreateListIfMissingAgent() : void
    {
        $this->jsonRequest('POST', '/listing', [
            'propertyReference' => 'some-reference',
        ]);

        $statusCode = $this->getResponseCode();
        self::assertEquals(400, $statusCode);
    }
}
