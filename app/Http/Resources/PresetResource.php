<?php

namespace App\Http\Resources;

use App\DTOs\PresetFiltersDTO;
use App\Models\Event;
use App\Models\Preset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \App\Models\Preset $resource
 */
class PresetResource extends JsonResource
{
    public function __construct(Preset $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'filters' => new PresetFiltersResource($this->filters),
            'metadata' => MetadataResource::make($this->whenLoaded('metadata')),
            'description' => $this->description,
            'sort_order' => $this->sort_order,
        ];
    }
}
