<?php

namespace App\RealCalendarContext\Controller;

use App\RealCalendarContext\Application\Queries\GetListingSlotsQuery\GetListingSlotsQuery;
use App\RealCalendarContext\Application\Queries\GetListingSlotsQuery\GetListingSlotsQueryHandler;
use App\RealCalendarContext\Application\ReadModel\ListingSlots\ListingSlot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetListingSlotsController extends AbstractController
{
    public function __construct(
        private GetListingSlotsQueryHandler $handler,
    ) {}

    #[Route('/listing/{id}/slots', methods: ['GET', 'OPTIONS'])]
    public function index(string $id): JsonResponse
    {
        $slots = $this->handler->handle(new GetListingSlotsQuery($id));

        return $this->json(array_map(fn(ListingSlot $item) => $item->toArray(), $slots));
    }
}
