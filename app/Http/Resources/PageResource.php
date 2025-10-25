<?php

namespace App\Http\Resources;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Page $resource
 */
class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'path' => $this->resource->path,
            'metadata' => MetadataResource::make($this->whenLoaded('metadata')),
            'content' => $this->resource->content,
            'title' => $this->resource->title,
        ];
    }
}
