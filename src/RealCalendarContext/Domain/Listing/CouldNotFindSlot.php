<?php

namespace App\RealCalendarContext\Domain\Listing;

use App\Shared\Domain\AggregateId;

final class CouldNotFindSlot extends \DomainException
{

    public static function covering(\DateTimeImmutable $instant, AggregateId $listingId) : self
    {
        return new self(
            sprintf(
                'Could not find slot %s in Listing %s.',
                $instant->format('Y-m-d H:i'),
                $listingId
            )
        );
    }
}
