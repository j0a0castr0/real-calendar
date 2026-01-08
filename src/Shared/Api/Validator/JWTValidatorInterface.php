<?php

namespace App\Shared\Api\Validator;

interface JWTValidatorInterface
{
    public function validate(string $jwt): array;
}
