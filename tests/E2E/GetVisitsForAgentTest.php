<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\Attributes\Test;

final class GetVisitsForAgentTest extends E2ETestCase
{
    #[Test]
    public function itShouldHave1VisitForAgent() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference'
        ]);
        $response = $this->getJsonResponse();
        $id = $response['data']['id'];

        $this->jsonRequestWithAuth('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => $visitDate = new \DateTimeImmutable('+1 hours')->format('c')
        ]);
        $this->jsonRequestWithAuth('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+4 hours')->format('c'),
            'from' => new \DateTimeImmutable('+3 hours')->format('c')
        ]);

        $payload = [
            'listingId'    => $id,
            'visitorName'  => 'John Doe',
            'visitorPhone' => '+351912345678',
            'visitorEmail' => 'john@example.com',
            'visitTime'    => $visitDate, // ISO 8601
        ];
        $this->jsonRequest('POST', '/visit', $payload);

        $this->jsonRequestWithAuth('GET', sprintf('/visit'));
        $response = $this->getJsonResponse();
        self::assertCount(1, $response);
    }

    #[Test]
    public function itShouldOnlyShowVisitsForRespectiveAgent() : void
    {
        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference'
        ]);
        $response = $this->getJsonResponse();
        $id = $response['data']['id'];

        $this->jsonRequestWithAuth('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => $visitDate = new \DateTimeImmutable('+1 hours')->format('c')
        ]);

        $this->jsonRequestWithAuth('POST', '/listing', [
            'propertyReference' => 'some-reference'
        ], 'john@doe.com');
        $response = $this->getJsonResponse();
        $id = $response['data']['id'];

        $this->jsonRequestWithAuth('POST', sprintf('/listing/%s/availability', $id), [
            'to' => new \DateTimeImmutable('+2 hours')->format('c'),
            'from' => $visitDate = new \DateTimeImmutable('+1 hours')->format('c')
        ], 'john@doe.com');

        $payload = [
            'listingId'    => $id,
            'visitorName'  => 'John Doe',
            'visitorPhone' => '+351912345678',
            'visitorEmail' => 'john@example.com',
            'visitTime'    => $visitDate, // ISO 8601
        ];
        $this->jsonRequest('POST', '/visit', $payload);

        $this->jsonRequestWithAuth('GET', sprintf('/visit'));
        $response = $this->getJsonResponse();
        self::assertCount(0, $response);
    }
}
