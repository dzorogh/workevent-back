<?php

namespace App\Http\Resources;

use App\Models\Tariff;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Tariff $resource
 */
class TariffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'price' => (int) $this->resource->price,
            'description' => $this->resource->description,
            'title' => $this->resource->title,
            'is_active' => $this->resource->is_active,
            'sort_order' => (int) $this->resource->sort_order,
        ];
    }
}
