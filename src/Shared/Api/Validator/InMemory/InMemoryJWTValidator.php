<?php

namespace App\Shared\Api\Validator\InMemory;

use App\Shared\Api\Validator\JWTValidatorInterface;

final class InMemoryJWTValidator implements JWTValidatorInterface
{
    /**
     * Validate a JWT and return claims.
     * For testing only — ignores signature and returns fake username/email.
     */
    public function validate(string $jwt): array
    {
        try {
            $parts = explode('.', $jwt);
            if (count($parts) !== 3) {
                throw new \RuntimeException('Invalid JWT format');
            }

            $payload = json_decode(base64_decode($parts[1]), true);

            return [
                'email' => $payload['email'] ?? 'test@agent.com',
            ];
        } catch (\Throwable $e) {
            // For testing, you might want to fail loudly if the JWT is malformed
            throw new \RuntimeException('Invalid JWT for test: ' . $e->getMessage());
        }
    }
}
