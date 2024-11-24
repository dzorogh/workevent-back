<?php

namespace App\DTOs;

use App\Models\Event;
use App\Models\Preset;
use Illuminate\Database\Eloquent\Collection;

readonly class EventSearchResult
{
    public function __construct(
        private readonly Event|Collection  $events,
        private readonly Preset|Collection $presets,
        private readonly PaginatorMetaDTO  $meta,
        private readonly array             $facets,
        private readonly array             $facets_stats,
    )
    {
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function getPresets()
    {
        return $this->presets;
    }

    public function getEvents()
    {
        return $this->events;
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
