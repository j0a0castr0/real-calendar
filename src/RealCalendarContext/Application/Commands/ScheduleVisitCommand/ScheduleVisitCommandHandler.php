<?php

namespace App\RealCalendarContext\Application\Commands\ScheduleVisitCommand;

use App\RealCalendarContext\Application\Commands\CommandResult;
use App\RealCalendarContext\Domain\Visit\ListingId;
use App\RealCalendarContext\Domain\Visit\Visit;
use App\RealCalendarContext\Domain\Visit\Visitor;
use App\RealCalendarContext\Domain\Visit\VisitRepository;
use App\RealCalendarContext\Domain\Visit\VisitTime;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class ScheduleVisitCommandHandler
{
    public function __construct(
        private readonly VisitRepository $visitRepository,
        private readonly LoggerInterface $logger = new NullLogger()
    ) {}

    public function handle(ScheduleVisitCommand $command)
    {
        try {
            $visit = Visit::schedule(
                new ListingId($command->listingId),
                Visitor::createFromValues($command->visitorName, $command->visitorPhone, $command->visitorEmail),
                VisitTime::startingAt($command->visitTime)
            );
            $this->visitRepository->save($visit);

            return CommandResult::success(
                ['id' => (string) $visit->id()],
            );
        } catch (\InvalidArgumentException $e) {
            return CommandResult::domainError($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error('Failed to add availability slot', ['exception' => $e]);
            return CommandResult::failure($e->getMessage());
        }
    }
}
