<?php

namespace App\Http\Resources;

use App\DTOs\EventSearchResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchEventsResource extends JsonResource
{
    /**
     * @param EventSearchResult $result
     */
    public function __construct(public EventSearchResult $result)
    {
        parent::__construct($result->getEvents());
    }

    public function toArray(Request $request): array
    {
        $meta = $this->result->getMeta();

        $presets = $this->result->getPresets();

        return [
            'data' => EventResource::collection($this->resource)->additional(['presets' => $presets]),

            'presets' => PresetResource::collection($presets),

            /** @var array<string, array<string>> */
            'facets' => $this->result->getFacets(),

            /** @var array<string, array<string, int>> */
            'facets_stats' => $this->result->getFacetsStats(),

            'meta' => [
                // explicitly shown for auto-documentation
                'last_page' => (int) $meta->last_page,
                'current_page' => (int)  $meta->current_page,
                'per_page' => (int) $meta->per_page,
                'total' => (int) $meta->total,
            ]
        ];
    }
}
