<?php

namespace App\Http\Resources;

use App\DTOs\EventSearchResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;

class SearchEventsResource extends JsonResource
{
    /**
     * @param EventSearchResult $result
     */
    public function __construct(public EventSearchResult $result)
    {
        parent::__construct($result->events);
    }

    public function toArray(Request $request): array
    {
        $meta = $this->result->getMeta();

        return [
            'data' => EventResource::collection($this->resource),

            /** @var array<string, array<string>> */
            'facets' => $this->result->getFacets(),

            /** @var array<string, array<string, int>> */
            'facets_stats' => $this->result->getFacetsStats(),

            'meta' => [
                'last_page' => (int) $meta->last_page,
                'current_page' => (int)  $meta->current_page,
                'per_page' => (int) $meta->per_page,
                'total' => (int) $meta->total,
            ]
        ];
    }
}
