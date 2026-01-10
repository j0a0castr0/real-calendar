<?php

namespace App\WywaContext\Domain\AwaySession;

use App\Shared\Domain\AggregateRoot;
use App\WywaContext\Domain\Shared\DemandId;

final class AwaySession extends AggregateRoot
{
    private bool $started = false;

    private function __construct()
    {
    }

    public function eventsForCaptureDemand(DemandId $demandId, string $leadReference, \DateTimeImmutable $capturedAt) : array
    {
        if ($this->started === false) return [];

        return [
            new DemandCaptured($this->id, (string) $demandId, $leadReference, $capturedAt)
        ];
    }

    public function eventsForEnd(\DateTimeImmutable $at): array
    {
        if (!$this->started) return [];
        return [ new AwaySessionEnded($this->id(), $at) ];
    }

    public function applyAwaySessionStarted(AwaySessionStarted $event) : void
    {
        $this->started = true;
    }

    public function applyDemandCaptured(DemandCaptured $event) : void
    {
    }

    public function applyAwaySessionEnded(AwaySessionEnded $event) : void
    {
        $this->started = false;
    }
}
