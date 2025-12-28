<?php

namespace App\Tests\Integration;

use App\Infrastrucutre\EventStore\EventStore;
use App\Infrastrucutre\EventStore\InMemory\InMemoryEventStore;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[Group('integration')]
abstract class IntegrationTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        static::getContainer()->set(
            EventStore::class,
            new InMemoryEventStore()
        );
    }
}
