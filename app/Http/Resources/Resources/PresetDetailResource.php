<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresetDetailResource extends JsonResource
{
    public function __construct($resource, private readonly array $searchResults)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'filters' => $this->filters,
            'events' => new SearchEventsResource(
                $this->searchResults['events'],
                $this->searchResults['facets']
            ),
        ];
    }
}
