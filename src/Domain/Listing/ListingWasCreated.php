<?php

namespace App\Domain\Listing;
use App\Domain\AbstractDomainEvent;

final class ListingWasCreated extends AbstractDomainEvent
{

    public function payload(): array
    {
        return [

        ];
    }
}
