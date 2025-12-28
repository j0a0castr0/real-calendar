<?php

namespace App\Controller;

use App\Application\Commands\CreateListingCommand\CreateListingCommand;
use App\Application\Commands\CreateListingCommand\CreateListingCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CreateListingController extends AbstractController
{
    public function __construct(private readonly CreateListingCommandHandler $handler) {}

    #[Route('/listing', methods: ['POST'])]
    public function index(): JsonResponse
    {

        $this->handler->handle(new CreateListingCommand());

        return $this->json([], 204);
    }
}
