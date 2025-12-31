<?php

namespace App\Application\Queries\GetListingSlotsQuery;

/**
 * @see GetListingSlotsQueryHandler
 */
final class GetListingSlotsQuery
{
    public function __construct(public readonly string $listingId)
    {
    }
}
