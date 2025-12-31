<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\Attributes\Test;

class ViewListingSlotsTest extends E2ETestCase
{
    #[Test]
    public function itShouldHave2Slots() : void
    {
        $this->jsonRequest('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);
        $response = $this->getJsonResponse();

        $id = $response['data']['id'];
        $this->jsonRequest('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => new \DateTimeImmutable('+1 hours')->format('c')
        ]);
        $this->jsonRequest('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+4 hours')->format('c'),
            'from' => new \DateTimeImmutable('+2 hours')->format('c')
        ]);

        $this->jsonRequest('GET', sprintf('/listing/%s/slots', $id));

        $response = $this->getJsonResponse();
        $statusCode = $this->getResponseCode();
        self::assertCount(2, $response);
        self::assertEquals(200, $statusCode);
    }

    #[Test]
    public function itShouldShowSlotsFromADifferentListing() : void
    {
        $this->jsonRequest('POST', '/listing', [
            'propertyReference' => 'some-reference',
            'agent' => 'agent'
        ]);
        $response = $this->getJsonResponse();
        $id = $response['data']['id'];
        $this->jsonRequest('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => new \DateTimeImmutable('+1 hours')->format('c')
        ]);


        $this->jsonRequest('POST', '/listing', [
            'propertyReference' => 'different-reference',
            'agent' => 'another'
        ]);
        $response = $this->getJsonResponse();
        $id = $response['data']['id'];
        $this->jsonRequest('GET', sprintf('/listing/%s/slots', $id));
        $response = $this->getJsonResponse();
        $statusCode = $this->getResponseCode();
        self::assertCount(0, $response);
        self::assertEquals(200, $statusCode);
    }
}
