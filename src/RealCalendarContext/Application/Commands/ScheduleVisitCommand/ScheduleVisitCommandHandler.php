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
                VisitTime::between($command->visitStartTime, $command->visitEndTime)
            );
            $this->visitRepository->save($visit);

            return CommandResult::success(
                ['id' => (string) $visit->id()],
            );
        } catch (\InvalidArgumentException $e) {
            return CommandResult::domainError($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error(
                sprintf('[ScheduleVisitCommand ]Something went wrong: %s %s', $e->getMessage(), $e->getTraceAsString()),
                ['exception' => $e
            ]);
            return CommandResult::failure($e->getMessage());
        }
    }
}
