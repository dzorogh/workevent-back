<?php

namespace App\Http\Resources;

use App\Models\Event;
use App\Traits\HasMetadataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

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

            /** @var string */
            'cover' =>  $this->resource->getFirstMediaUrl('cover'),

            /** @var array<string> */
            'gallery' => $this->resource->getMedia('gallery')->map(fn ($media) => $media->getUrl()),

            'start_date' => $this->resource->start_date?->format('Y-m-d'),
            'end_date' => $this->resource->end_date?->format('Y-m-d'),
            'format' => $this->resource->format,
            'format_label' => $this->resource->format->getLabel(),
            'website' => $this->resource->website,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,

            'sort_order' => $this->resource->sort_order,
            'city_id' => $this->resource->city_id,
            'city' => CityResource::make($this->whenLoaded('city')),
            'industry_id' => $this->resource->industry_id,
            'industry' => CityResource::make($this->whenLoaded('industry')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'metadata' => MetadataResource::make($this->whenLoaded('metadata')),
            'tariffs' => TariffResource::collection($this->whenLoaded('tariffs')),
        ];
    }
}
