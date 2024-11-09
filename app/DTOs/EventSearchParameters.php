<?php

namespace App\DTOs;

readonly class EventSearchParameters
{
    public function __construct(
        public ?string $query = null,
        public ?string $format = null,
        public ?int $cityId = null,
        public ?int $industryId = null,
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public int $page = 1,
        public int $perPage = 12,
    ) {}

    public static function fromArray(array $params): self
    {
        return new self(
            query: $params['query'] ?? null,
            format: $params['format'] ?? null,
            cityId: $params['city_id'] ?? null,
            industryId: $params['industry_id'] ?? null,
            dateFrom: $params['date_from'] ?? null,
            dateTo: $params['date_to'] ?? null,
            page: $params['page'] ?? 1,
            perPage: $params['per_page'] ?? 12,
        );
    }
}
