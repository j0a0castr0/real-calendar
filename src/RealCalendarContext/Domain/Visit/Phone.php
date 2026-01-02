<?php

namespace App\RealCalendarContext\Domain\Visit;

final class Phone
{
    public function __construct(
        private string $phone
    ) {
        self::ensureValidPhone($phone);
    }

    private static function ensureValidPhone(string $phone): void
    {
        // Remove spaces, dashes, parentheses for validation
        $normalized = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Must start with optional +, then digits only, length 7–15 digits
        if (!preg_match('/^\+?\d{7,15}$/', $normalized)) {
            throw new \InvalidArgumentException(sprintf('Invalid phone number: "%s"', $phone));
        }
    }

    public function __toString(): string
    {
        return $this->phone;
    }
}
