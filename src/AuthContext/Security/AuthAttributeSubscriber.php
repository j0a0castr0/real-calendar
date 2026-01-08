<?php

namespace App\AuthContext\Security;

use App\AuthContext\Security\Attribute\IsAuthenticated;
use App\Shared\Api\Validator\JWTValidatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class AuthAttributeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private JWTValidatorInterface $validator
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 10],
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        // Skip if controller is not a class method
        if (!is_array($controller)) return;

        [$controllerInstance, $method] = $controller;

        $reflectionMethod = new \ReflectionMethod($controllerInstance, $method);

        // Check if the method or class has the attribute
        $attributes = array_merge(
            $reflectionMethod->getAttributes(IsAuthenticated::class),
            $reflectionMethod->getDeclaringClass()->getAttributes(IsAuthenticated::class)
        );

        if (empty($attributes)) return; // no attribute → skip

        $request = $event->getRequest();
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new AccessDeniedHttpException('Missing access token');
        }

        $jwt = substr($authHeader, 7);

        try {
            $this->validator->validate($jwt, 'access');// ensure this is an access token
            // Optionally store user info in request attributes
            // $request->attributes->set('user', $decodedClaims);
        } catch (\Throwable $e) {
            throw new AccessDeniedHttpException('Invalid access token');
        }
    }
}
