<?php

namespace App\DTOs;

class EventSearchParameters
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
        public ?bool $isPriority = null,
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
            isPriority: $params['is_priority'] ?? false,
        );
    }

    public function setDateFrom(string $date): void
    {
        $this->dateFrom = $date;
    }

    public function setDateTo(string $date): void
    {
        $this->dateTo = $date;
    }

    public function toArray()
    {
        return [
            'query' => $this->query,
            'format' => $this->format,
            'city_id' => $this->cityId,
            'industry_id' => $this->industryId,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'page' => $this->page,
            'per_page' => $this->perPage,
            'is_priority' => $this->isPriority,
        ];
    }
}
