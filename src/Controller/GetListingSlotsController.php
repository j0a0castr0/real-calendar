<?php

namespace App\Controller;

use App\Application\ReadModel\ListingSlots\ListingSlot;
use App\Application\ReadModel\ListingSlots\ListingSlotsReadModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetListingSlotsController extends AbstractController
{
    public function __construct(
        private ListingSlotsReadModel $listingSlotsReadModel,
    ) {}

    #[Route('/listing/{id}/slots', methods: ['GET'])]
    public function index(string $id): JsonResponse
    {
        $slots = $this->listingSlotsReadModel->getAllForListing($id);

        return $this->json(array_map(fn(ListingSlot $item) => $item->toArray(), $slots));
    }
}
