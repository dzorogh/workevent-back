<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource,
        ];
    }
}