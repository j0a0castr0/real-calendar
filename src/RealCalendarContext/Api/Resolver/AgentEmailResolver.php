<?php

namespace App\RealCalendarContext\Api\Resolver;

use App\Shared\Validator\JWTValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AgentEmailResolver implements ValueResolverInterface
{
    private JWTValidatorInterface $validator;

    public function __construct(JWTValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $authHeader = $request->headers->get('Authorization');

        // Optional: if the route allows public access, return null
        $isOptional = $argument->getAttributes()['optional'] ?? false;

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            if ($isOptional) {
                yield null; // public route → no email
                return;
            }
            throw new AccessDeniedHttpException('Missing token');
        }

        $jwt = substr($authHeader, 7);

        try {
            $data = $this->validator->validate($jwt);
            yield $data['email'];
        } catch (\Throwable $e) {
            if ($isOptional) {
                yield null;
            } else {
                throw new AccessDeniedHttpException('Invalid token');
            }
        }
    }
}
