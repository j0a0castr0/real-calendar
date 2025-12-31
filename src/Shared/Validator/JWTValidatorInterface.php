<?php

namespace App\Shared\Validator;

interface JWTValidatorInterface
{
    public function validate(string $jwt): array;
}
