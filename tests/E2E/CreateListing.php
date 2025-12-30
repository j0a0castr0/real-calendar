<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\Attributes\Test;

final class CreateListing extends E2ETestCase
{
    #[Test]
    public function itShouldCreateListing() : void
    {
        $this->jsonRequest('POST', '/listing', [
        ]);

        $response = $this->getJsonResponse();
        $statusCode = $this->getResponseCode();
        self::assertEquals(204, $statusCode);
    }
}
