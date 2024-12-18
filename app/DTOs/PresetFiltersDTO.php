<?php

namespace App\DTOs;

use App\Enums\EventFormat;

class PresetFiltersDTO
{
    public function __construct(
        public ?EventFormat $format = null,
        public ?int $city_id = null,
        public ?int $industry_id = null,
    ) {}

    public static function fromArray(array $params): self
    {
        return new self(
            format: $params['format'] ?? null,
            city_id: $params['city_id'] ?? null,
            industry_id: $params['industry_id'] ?? null,
        );
    }
} 