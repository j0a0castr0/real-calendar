<?php

namespace App\Shared\Infrastructure\EventUpcasting;

final class EventUpcasterRegistry
{
    public function upcast(string $eventClass, int $fromVersion, int $toVersion, array $payload): array
    {
        // If versions differ and we don't have upcasters, fail loudly.
        throw new \RuntimeException("Missing upcaster for {$eventClass} from v{$fromVersion} to v{$toVersion}");
    }
}
