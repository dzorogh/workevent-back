<?php

namespace App\Services;

use App\Http\Requests\Api\V1\SearchEventsRequest;
use App\Models\Event;
use Meilisearch\Endpoints\Indexes;
use App\DTOs\EventSearchParameters;

class EventSearchService
{
    public function search(SearchEventsRequest $request): array
    {
        $searchParams = EventSearchParameters::fromArray($request->validated());

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
            return $meiliSearch->search($query, [
                'facets' => $params->facets,
                'page' => $params->page,
                'hitsPerPage' => $params->perPage,
            ]);
        })
            ->when($params->format, fn($query) => $query->where('format', $params->format))
            ->when($params->cityId, fn($query) => $query->where('city_id', $params->cityId))
            ->when($params->industryId, fn($query) => $query->where('industry_id', $params->industryId))
            ->when($params->dateFrom, fn($query) => $query->where('start_date', '>=', $params->dateFrom))
            ->when($params->dateTo, fn($query) => $query->where('end_date', '<=', $params->dateTo));
    }

    private function paginateResults($query, int $perPage)
    {
        return $query->paginate($perPage);
    }
}
