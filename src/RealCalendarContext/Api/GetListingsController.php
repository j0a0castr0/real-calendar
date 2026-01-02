<?php

namespace App\RealCalendarContext\Api;

use App\AuthContext\Security\Attribute\IsAuthenticated;
use App\RealCalendarContext\Application\Queries\GetListingsQuery\GetListingsQuery;
use App\RealCalendarContext\Application\Queries\GetListingsQuery\GetListingsQueryHandler;
use App\RealCalendarContext\Application\ReadModel\Listing\Listing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetListingsController extends AbstractController
{
    public function __construct(private GetListingsQueryHandler $handler) {}

    #[Route('/listing', methods: ['GET', 'OPTIONS'])]
    #[IsAuthenticated]
    public function index(string $agentEmail): JsonResponse
    {
        $listings = $this->handler->handle(new GetListingsQuery($agentEmail));

        return $this->json(array_map(fn(Listing $listing) => $listing->toArray(), $listings));
    }
}
