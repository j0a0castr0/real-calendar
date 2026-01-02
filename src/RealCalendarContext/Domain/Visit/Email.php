<?php

namespace App\RealCalendarContext\Domain\Visit;

final class Email
{
    public function __construct(
        private string $email
    ) {
        self::ensureValidEmail($email);
    }

    private static function ensureValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('Invalid email address: "%s"', $email));
        }
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
