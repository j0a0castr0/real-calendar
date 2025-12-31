<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\Attributes\Test;

class CreateAndGetListingTest extends E2ETestCase
{
    #[Test]
    public function itShouldGet2NewlyCreatedListings() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);

        $this->jsonRequestWithAuth('GET', '/listing');

        $response = $this->getJsonResponse();
        $statusCode = $this->getResponseCode();
        self::assertEquals(200, $statusCode);
        self::assertCount(2, $response);
    }

    #[Test]
    public function itShouldGet1NewlyCreatedListingWithCorrectAgentAndPropertyReference() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);

        $this->jsonRequest('GET', '/listing');

        $response = $this->getJsonResponse();
        $statusCode = $this->getResponseCode();
        self::assertEquals(200, $statusCode);
        self::assertCount(1, $response);

        self::assertEquals('some-reference', $response[0]['propertyReference']);
        self::assertEquals('agent', $response[0]['agent']);
    }
}
