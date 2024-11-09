<?php

namespace App\Services;

use App\Models\Event;
use Meilisearch\Endpoints\Indexes;

class EventSearchService
{
    private const DEFAULT_PER_PAGE = 4;
    private const FACET_ATTRIBUTES = [
        'city_id',
        'format',
        'industry_id'
    ];

    public function search(array $params): array
    {
        $perPage = $params['per_page'] ?? self::DEFAULT_PER_PAGE;
        $page = $params['page'] ?? 1;

        $query = $this->buildSearchQuery($params, $page, $perPage);
        $events = $this->paginateResults($query, $perPage);
        $facets = $query->raw()['facetDistribution'] ?? [];

        return [
            'events' => $events,
            'facets' => $facets,
        ];
    }

    private function buildSearchQuery(array $params, int $page, int $perPage)
    {
        return Event::search($params['query'] ?? null, function (Indexes $meiliSearch, ?string $query, array $options) use ($page, $perPage) {
            return $meiliSearch->search($query, [
                'facets' => self::FACET_ATTRIBUTES,
                'page' => $page,
                'hitsPerPage' => $perPage,
            ]);
        })
            ->when(isset($params['format']), fn($query) => $query->where('format', $params['format']))
            ->when(isset($params['city_id']), fn($query) => $query->where('city_id', $params['city_id']))
            ->when(isset($params['industry_id']), fn($query) => $query->where('industry_id', $params['industry_id']))
            ->when(isset($params['date_from']), fn($query) => $query->where('start_date', '>=', $params['date_from']))
            ->when(isset($params['date_to']), fn($query) => $query->where('end_date', '<=', $params['date_to']));
    }

    private function paginateResults($query, int $perPage)
    {
        return $query->paginate($perPage);
    }
} 