<?php

namespace App\Application\ReadModel\Listing;

final readonly class Listing
{
    public function __construct(private string $id){}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
