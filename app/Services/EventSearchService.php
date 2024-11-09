<?php

namespace App\Services;

use App\Http\Requests\Api\V1\SearchEventsRequest;
use App\Models\Event;
use Meilisearch\Endpoints\Indexes;
use App\DTOs\EventSearchParameters;

class EventSearchService
{
    public function search(EventSearchParameters $searchParams): array
    {
        $query = $this->buildSearchQuery($searchParams);
        $events = $this->paginateResults($query, $searchParams->perPage);
        $facets = $query->raw()['facetDistribution'] ?? [];

        return [
            'events' => $events,
            'facets' => $facets,
        ];
    }

    private function buildSearchQuery(EventSearchParameters $params): \Laravel\Scout\Builder
    {
        return Event::search($params->query, function (Indexes $meiliSearch, ?string $query, array $options) use ($params) {
            $filter = $this->buildMeiliSearchFilter($params);

            return $meiliSearch->search($query, [
                'facets' => ['*'],
                'page' => $params->page,
                'hitsPerPage' => $params->perPage,
                'filter' => $filter,
            ]);
        });
    }

    private function buildMeiliSearchFilter(EventSearchParameters $params): array
    {
        $filters = [];

        if ($params->format) {
            $filters[] = "format = '{$params->format}'";
        }
        if ($params->cityId) {
            $filters[] = "city_id = {$params->cityId}";
        }
        if ($params->industryId) {
            $filters[] = "industry_id = {$params->industryId}";
        }
        if ($params->dateFrom) {
            $filters[] = "start_date >= {$params->dateFrom}";
        }
        if ($params->dateTo) {
            $filters[] = "end_date <= {$params->dateTo}";
        }

        return $filters;
    }

    private function paginateResults($query, int $perPage)
    {
        return $query->paginate($perPage);
    }
}
