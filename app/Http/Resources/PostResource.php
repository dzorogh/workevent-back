<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->whenHas('content', $this->content),
            'cover' => $this->getFirstMediaUrl('cover'),
            'created_at' => $this->whenHas('created_at', $this->created_at),
            'updated_at' => $this->whenHas('updated_at', $this->updated_at),
            'deleted_at' => $this->whenHas('deleted_at', $this->deleted_at),
        ];
    }
}
