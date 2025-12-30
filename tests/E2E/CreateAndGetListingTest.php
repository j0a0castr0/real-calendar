<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\Attributes\Test;

class CreateAndGetListingTest extends E2ETestCase
{
    #[Test]
    public function itShouldGet2NewlyCreatedListings() : void
    {
        $this->jsonRequest('POST', '/listing');
        $this->jsonRequest('POST', '/listing');

        $this->jsonRequest('GET', '/listing');

        $response = $this->getJsonResponse();
        $statusCode = $this->getResponseCode();
        self::assertEquals(200, $statusCode);
        self::assertCount(2, $response);
    }
}
