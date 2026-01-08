<?php

namespace App\Tests\RealCalendarContext\E2E\Visit;

use App\Tests\Shared\E2ETestCase;
use PHPUnit\Framework\Attributes\Test;

final class ScheduleVisitTest extends E2ETestCase
{
    #[Test]
    public function itShouldScheduleAVisit() : void
    {
        $payload = [
            'listingId'    => '8c3c9888-24c0-497f-887b-27a1572eb90c',
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
