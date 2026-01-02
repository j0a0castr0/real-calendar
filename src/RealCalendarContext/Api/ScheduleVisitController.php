<?php

namespace App\RealCalendarContext\Api;

use App\RealCalendarContext\Application\Commands\CommandResultType;
use App\RealCalendarContext\Application\Commands\ScheduleVisitCommand\ScheduleVisitCommand;
use App\RealCalendarContext\Application\Commands\ScheduleVisitCommand\ScheduleVisitCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ScheduleVisitController extends AbstractController
{
    public function __construct(private readonly ScheduleVisitCommandHandler $handler) {}

    #[Route('/visit', methods: ['POST', 'OPTIONS'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset(
            $data['listingId'],
            $data['visitorName'],
            $data['visitorPhone'],
            $data['visitorEmail'],
        )) {
            return $this->json(['error' => 'Missing propertyReference or agent fields'], 400);
        }

        try {
            $visitTime = new \DateTimeImmutable($data['visitTime']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid date format'], 400);
        }

        $result = $this->handler->handle(new ScheduleVisitCommand(
            $data['listingId'],
            $data['visitorName'],
            $data['visitorPhone'],
            $data['visitorEmail'],
            $visitTime
        ));

        return match ($result->type()) {
            CommandResultType::SUCCESS => $this->json([
                'message' => $result->message(),
                'data' => $result->data()
            ], 201),

            CommandResultType::DOMAIN_ERROR => $this->json([
                'error' => $result->message()
            ], 400),

            default => $this->json([
                'error' => 'Internal Server Error'
            ], 500),
        };
    }
}
