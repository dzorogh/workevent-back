<?php

namespace App\Http\Resources;

use App\Models\Event;
use App\Traits\HasMetadataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Event $resource
 */
class EventResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'cover' =>  $this->resource->getFirstMediaUrl('cover'),
            'gallery' => $this->resource->getMedia('gallery')->map(fn ($media) => $media->getUrl()),
            'start_date' => $this->resource->start_date?->format('Y-m-d'),
            'end_date' => $this->resource->end_date?->format('Y-m-d'),
            'format' => $this->resource->format,
            'website' => $this->resource->website,
            'sort_order' => $this->resource->sort_order,
            'city_id' => $this->resource->city_id,
            'city' => CityResource::make($this->whenLoaded('city')),
            'industry_id' => $this->resource->industry_id,
            'industry' => CityResource::make($this->whenLoaded('industry')),
            'tags' => CityResource::collection($this->whenLoaded('tags')),
            'metadata' => CityResource::make($this->whenLoaded('metadata')),
        ];
    }
}
