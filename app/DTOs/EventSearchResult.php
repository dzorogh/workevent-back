<?php

namespace App\DTOs;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

readonly class EventSearchResult
{
    public function __construct(
        public readonly Event|Collection $events,
        public readonly PaginatorMetaDTO $meta,
        public readonly array            $facets,
        public readonly array            $facets_stats,
    )
    {
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function getFacets()
    {
        return $this->facets;
    }

    public function getFacetsStats()
    {
        return $this->facets_stats;
    }
}
