<?php

namespace App\Controller\RealCalendar;

use App\Application\Commands\AddAvailabilitySlotCommand\AddAvailabilitySlotCommand;
use App\Application\Commands\AddAvailabilitySlotCommand\AddAvailabilitySlotCommandHandler;
use App\Application\Commands\CommandResultType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AddAvailabilitySlotController extends AbstractController
{
    public function __construct(private readonly AddAvailabilitySlotCommandHandler $handler) {}

    #[Route('/listing/{id}/availability', methods: ['POST', 'OPTIONS'])]
    public function index(Request $request, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['from'], $data['to'])) {
            return $this->json(['error' => 'Missing from or to fields'], 400);
        }

        try {
            $from = new \DateTimeImmutable($data['from']);
            $to = new \DateTimeImmutable($data['to']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid date format'], 400);
        }

        $result = $this->handler->handle(new AddAvailabilitySlotCommand(
            listingId: $id,
            from: $from,
            to: $to
        ));

        return match ($result->type()) {
            CommandResultType::SUCCESS => $this->json([
                'message' => $result->message(),
                'data' => $result->data()
            ], 200),

            CommandResultType::DOMAIN_ERROR => $this->json([
                'error' => $result->message()
            ], 400),

            CommandResultType::NOT_FOUND => $this->json([
                'error' => $result->message()
            ], 404),

            CommandResultType::FAILURE => $this->json([
                'error' => 'Internal Server Error'
            ], 500),
        };
    }
}
