<?php

namespace App\WywaContext\Domain\AwaySession;

use App\Shared\Domain\AggregateId;
use App\Shared\Domain\AggregateRoot;
use App\WywaContext\Domain\Shared\DemandId;

final class AwaySession extends AggregateRoot
{
    private bool $started = false;

    /**
     * @var array<string, \DateTimeImmutable>
     */
    private array $demandsCaptured = [];

    private function __construct()
    {
    }

    public static function start(\DateTimeImmutable $at, ?AggregateId $id = null) : self
    {
        return self::reconstitute($aggregateId = $id ?? new AggregateId(), [])
            ->withEvents([
                new AwaySessionStarted($aggregateId, $at),
            ]);
    }

    public function eventsForCaptureDemand(DemandId $demandId, \DateTimeImmutable $capturedAt) : array
    {
        if ($this->started === false) return [];
        if (isset($this->demandsCaptured[(string) $demandId])) return [];

        return [
            new DemandCaptured($this->id, (string) $demandId, $capturedAt)
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
        $this->demandsCaptured[(string) $event->demandId()] = $event->occurredAt();
    }

    public function applyAwaySessionEnded(AwaySessionEnded $event) : void
    {
        $this->started = false;
    }
}
