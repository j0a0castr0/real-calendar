<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\Attributes\Test;

final class CreateListingTest extends E2ETestCase
{
    #[Test]
    public function itShouldCreateListing() : void
    {
        $this->jsonRequest('POST', '/listing', [
        ]);

        $response = $this->getJsonResponse();
        $statusCode = $this->getResponseCode();
        self::assertEquals(201, $statusCode);
    }
}
