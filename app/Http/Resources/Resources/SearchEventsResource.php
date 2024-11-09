<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchEventsResource extends JsonResource
{
    public function __construct(private $events, private array $facets)
    {
        parent::__construct($events);
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => EventResource::collection($this->resource),
            'facets' => [
                'formats' => $this->facets['format'] ?? [],
                'cities' => $this->facets['city_id'] ?? [],
                'industries' => $this->facets['industry_id'] ?? [],
            ],
            'meta' => [
                'total' => $this->resource->total(),
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
            ],
        ];
    }
}
