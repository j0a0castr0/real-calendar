<?php

namespace App\Controller;

use App\Application\Commands\CommandResultType;
use App\Application\Commands\CreateListingCommand\CreateListingCommand;
use App\Application\Commands\CreateListingCommand\CreateListingCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CreateListingController extends AbstractController
{
    public function __construct(private readonly CreateListingCommandHandler $handler) {}

    #[Route('/listing', methods: ['POST', 'OPTIONS'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['propertyReference'], $data['agent'])) {
            return $this->json(['error' => 'Missing propertyReference or agent fields'], 400);
        }

        $result = $this->handler->handle(new CreateListingCommand(
            $data['propertyReference'],
            $data['agent'],
        ));

        return match ($result->type()) {
            CommandResultType::SUCCESS => $this->json([
                'message' => $result->message(),
                'data' => $result->data()
            ], 201),

            default => $this->json([
                'error' => 'Internal Server Error'
            ], 500),
        };
    }
}
