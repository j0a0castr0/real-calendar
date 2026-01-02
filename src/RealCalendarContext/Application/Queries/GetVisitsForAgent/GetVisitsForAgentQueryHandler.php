<?php

namespace App\RealCalendarContext\Application\Queries\GetVisitsForAgent;

use App\RealCalendarContext\Application\ReadModel\Visit\Visit;
use App\RealCalendarContext\Application\ReadModel\Visit\VisitReadModel;

final class GetVisitsForAgentQueryHandler
{
    public function __construct(private readonly VisitReadModel $visitReadModel) {}

    /**
     * @return Visit[]
     */
    public function handle(GetVisitsForAgentQuery $query) : array
    {
        return $this->visitReadModel->getByAgent($query->agent);
    }
}
