<?php

namespace App\RealCalendarContext\Api;

use App\AuthContext\Security\Attribute\IsAuthenticated;
use App\RealCalendarContext\Application\Queries\GetVisitsForAgent\GetVisitsForAgentQuery;
use App\RealCalendarContext\Application\Queries\GetVisitsForAgent\GetVisitsForAgentQueryHandler;
use App\RealCalendarContext\Application\ReadModel\Visit\Visit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetVisitsForAgentController extends AbstractController
{
    public function __construct(private GetVisitsForAgentQueryHandler $handler) {}

    #[Route('/visit', methods: ['GET', 'OPTIONS'])]
    #[IsAuthenticated]
    public function index(string $agentEmail): JsonResponse
    {
        $visits = $this->handler->handle(new GetVisitsForAgentQuery($agentEmail));

        return $this->json(array_map(fn(Visit $visit) => $visit->toArray(), $visits));
    }
}
