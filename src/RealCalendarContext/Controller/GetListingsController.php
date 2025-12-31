<?php

namespace App\RealCalendarContext\Controller;

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
    public function index(): JsonResponse
    {
        $listings = $this->handler->handle(new GetListingsQuery());

        return $this->json(array_map(fn(Listing $listing) => $listing->toArray(), $listings));
    }
}
