<?php

namespace App\Services;

use Laravel\Scout\Builder;
use App\DTOs\EventSearchParameters;
use App\DTOs\EventSearchResult;
use App\DTOs\PaginatorMetaDTO;
use App\DTOs\PresetFiltersDTO;
use App\Models\Event;
use Meilisearch\Endpoints\Indexes;
use Meilisearch\Search\SearchResult;
use App\Enums\EventFormat;

class EventSearchService
{
    public function __construct(
        private readonly PresetService $presetService
    ) {}

    public function search(EventSearchParameters $params): EventSearchResult
    {
        $query = $this->buildSearchQuery($params);

        $searchResult = new SearchResult($query->raw());

        $events = Event::hydrate($searchResult->getHits());

        $events->load(['city', 'industry', 'industries', 'media']);

        $meta = new PaginatorMetaDTO(
            current_page: $searchResult->getPage(),
            per_page: $searchResult->getHitsPerPage(),
            last_page: $searchResult->getTotalPages(),
            total: $searchResult->getTotalHits()
        );

        $presetFilters = new PresetFiltersDTO(
            format: EventFormat::tryFrom($params->format),
            city_id: $params->cityId,
            industry_id: $params->industryId
        );

        $presets = $this->presetService->getPresets($presetFilters);

        return new EventSearchResult(
            events: $events,
            presets: $presets,
            meta: $meta,
            facets: $searchResult->getFacetDistribution(),
            facets_stats: $searchResult->getFacetStats()
        );
    }

    private function buildSearchQuery(EventSearchParameters $params): Builder
    {
        return Event::search($params->query, function (Indexes $meiliSearch, ?string $query, array $options) use ($params) {
            $filter = $this->buildFilters($params);

            return $meiliSearch->search($query, [
                'facets' => ['*'],
                'page' => $params->page,
                'hitsPerPage' => $params->perPage,
                'filter' => $filter,
                'sort' => $query ? null : ['start_date:asc'],
                'attributesToRetrieve' => [
                    'id',
                    'title',
                    'slug',
                    'start_date',
                    'end_date',
                    'format',
                    'city_id',
                    'industry_id',
                    'industries_ids',
                    'is_priority',
                    'is_active',
                ]
            ]);
        });
    }

    private function buildFilters(EventSearchParameters $params): array
    {
        $filters = [];

        $filters[] = "__soft_deleted = 0";

        if ($params->format) {
            $filters[] = "format = '{$params->format}'";
        }
        if ($params->cityId) {
            $filters[] = "city_id = {$params->cityId}";
        }
        if ($params->industryId) {
            // search by industry_id or industries_ids
            $filters[] = [
                "industry_id = {$params->industryId}",
                "industries_ids = {$params->industryId}",
            ];
        }
        if ($params->dateFrom) {
            $filters[] = "start_date >= {$params->dateFrom}";
        }
        if ($params->dateTo) {
            $filters[] = "end_date <= {$params->dateTo}";
        }
        if ($params->isPriority) {
            $filters[] = "is_priority = true";
        }

        return $filters;
    }
}
