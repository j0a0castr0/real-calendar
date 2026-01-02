<?php

namespace App\Tests\E2E\Visit;

use App\Tests\E2E\E2ETestCase;
use PHPUnit\Framework\Attributes\Test;

final class ScheduleVisitTest extends E2ETestCase
{
    #[Test]
    public function itShouldScheduleAVisit() : void
    {
        $payload = [
            'listingId'    => 'listing-123',
            'visitorName'  => 'John Doe',
            'visitorPhone' => '+351912345678',
            'visitorEmail' => 'john@example.com',
            'visitTime'    => (new \DateTimeImmutable('+1 day'))->format('c'), // ISO 8601
        ];

        $this->jsonRequest('POST', '/visit', $payload);

        $statusCode = $this->getResponseCode();
        self::assertEquals(201, $statusCode);
    }
}
