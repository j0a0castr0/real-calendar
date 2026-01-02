<?php

namespace App\RealCalendarContext\Domain\Visit;

final class Contact
{
    public function __construct(
        private Phone $phone,
        private Email $email
    ) { }

    public function serialize(): array
    {
        return [
            'phone' => (string) $this->phone,
            'email' => (string) $this->email,
        ];
    }
}
