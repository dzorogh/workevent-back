<?php

namespace App\Http\Resources\Resources;

use App\Traits\HasMetadataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'cover' => [
                'original' => $this->getFirstMediaUrl('cover'),
                'thumb' => $this->getFirstMediaUrl('cover', 'thumb'),
                'preview' => $this->getFirstMediaUrl('cover', 'preview'),
            ],
            'gallery' => $this->getMedia('gallery')->map(fn ($media) => [
                'id' => $media->id,
                'original' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'preview' => $media->getUrl('preview'),
            ]),
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'format' => $this->format,
            'website' => $this->website,
            'city' => [
                'id' => $this->city?->id,
                'title' => $this->city?->title,
            ],
            'industry' => [
                'id' => $this->industry?->id,
                'title' => $this->industry?->title,
            ],
            'tags' => $this->tags->map(fn($tag) => [
                'id' => $tag->id,
                'title' => $tag->title,
            ]),
            'sort_order' => $this->sort_order,
            'metadata' => new MetadataResource($this->metadata),
        ];
    }
}
