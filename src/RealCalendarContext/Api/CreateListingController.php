<?php

namespace App\RealCalendarContext\Api;

use App\RealCalendarContext\Application\Commands\CommandResultType;
use App\RealCalendarContext\Application\Commands\CreateListingCommand\CreateListingCommand;
use App\RealCalendarContext\Application\Commands\CreateListingCommand\CreateListingCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CreateListingController extends AbstractController
{
    public function __construct(private readonly CreateListingCommandHandler $handler) {}

    #[Route('/listing', methods: ['POST', 'OPTIONS'])]
    public function index(Request $request, string $agentEmail): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['propertyReference'])) {
            return $this->json(['error' => 'Missing propertyReference fields'], 400);
        }

        $result = $this->handler->handle(new CreateListingCommand(
            $data['propertyReference'],
            $agentEmail,
        ));

        return match ($result->type()) {
            CommandResultType::SUCCESS => $this->json([
                'message' => $result->message(),
                'data' => $result->data()
            ], 201),
            CommandResultType::DOMAIN_ERROR => $this->json([
                'error' => $result->message(),
            ], 400),
            default => $this->json([
                'error' => 'Internal Server Error'
            ], 500),
        };
    }
}
