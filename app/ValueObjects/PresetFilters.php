<?php

namespace App\ValueObjects;

use App\Enums\EventFormat;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class PresetFilters implements Arrayable, JsonSerializable 
{
    public function __construct(
        public ?string $format = null,
        public ?int $city_id = null,
        public ?int $industry_id = null,
    ) {}

    public function toArray(): array
    {
        return [
            'format' => $this->format,
            'city_id' => $this->city_id,
            'industry_id' => $this->industry_id,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function fromArray(array $filters): self
    {
        return new self(
            format: $filters['format'] ?? null,
            city_id: $filters['city_id'] ?? null,
            industry_id: $filters['industry_id'] ?? null,
        );
    }
}   