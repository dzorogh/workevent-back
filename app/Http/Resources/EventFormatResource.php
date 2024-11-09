<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventFormatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->value,
            'name' => $this->getLabel(),
            'color' => $this->getColor(),
        ];
    }
} 