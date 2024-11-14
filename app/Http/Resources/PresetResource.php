<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \App\Models\Preset $resource
 */
class PresetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'filters' => new PresetFiltersResource($this->filters),
            'events' => EventResource::collection($this->whenLoaded('events')),
        ];
    }
}
