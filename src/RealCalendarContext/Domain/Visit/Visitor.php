<?php

namespace App\RealCalendarContext\Domain\Visit;

class Visitor
{
    private function __construct(
        private Name $name,
        private Contact $contact
    ) { }

    public static function createFromValues(string $name, string $phone, string $email) : self
    {
        return new self(
            new Name($name),
            new Contact(
                new Phone($phone),
                new Email($email)
            )
        );
    }

    public static function deserialize(array $visitor) : self
    {
        return new self(
            new Name($visitor['name']),
            Contact::deserialize($visitor['contact']),
        );
    }

    public function serialize() : array
    {
        return [
            'name' => (string) $this->name,
            'contact' => $this->contact->serialize(),
        ];
    }

    public function nameAsString() : string
    {
        return (string) $this->name;
    }

    public function contact() : Contact
    {
        return $this->contact;
    }
}
