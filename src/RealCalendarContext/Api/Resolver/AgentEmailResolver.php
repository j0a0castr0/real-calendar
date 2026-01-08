<?php

namespace App\RealCalendarContext\Api\Resolver;

use App\Shared\Api\Validator\JWTValidatorInterface;
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
        // ✅ Read ID token, not Authorization header
        $idToken = $request->headers->get('X-ID-Token');

        $isOptional = $argument->getAttributes()['optional'] ?? false;

        if (!$idToken) {
            if ($isOptional) {
                yield null; // public route → no email
                return;
            }
            throw new AccessDeniedHttpException('Missing ID token');
        }

        try {
            // ✅ Validate as ID token
            $data = $this->validator->validate($idToken, 'id');
            yield $data['email']; // now correctly from ID token
        } catch (\Throwable $e) {
            if ($isOptional) {
                yield null;
            } else {
                throw new AccessDeniedHttpException('Invalid ID token');
            }
        }
    }
}
