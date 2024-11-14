<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \App\DTOs\PresetFiltersDTO $resource
 */
class PresetFiltersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'format' => $this->resource->format,
            'city_id' => $this->resource->city_id,
            'industry_id' => $this->resource->industry_id,
        ];
    }
}
