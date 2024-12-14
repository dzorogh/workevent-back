<?php

namespace App\Services;

use App\DTOs\EventSearchParameters;
use App\DTOs\EventSearchResult;
use App\DTOs\PaginatorMetaDTO;
use App\DTOs\PresetFiltersDTO;
use App\Models\Event;
use App\Models\Preset;
use Meilisearch\Endpoints\Indexes;
use Meilisearch\Search\SearchResult;

class EventSearchService
{
    public function search(EventSearchParameters $params): EventSearchResult
    {
        $query = $this->buildSearchQuery($params);

        $searchResult = new SearchResult($query->raw());

        $events = Event::hydrate($searchResult->getHits());

        $events->load(['city', 'industry', 'media']);

        $meta = new PaginatorMetaDTO(
            current_page: $searchResult->getPage(),
            per_page: $searchResult->getHitsPerPage(),
            last_page: $searchResult->getTotalPages(),
            total: $searchResult->getTotalHits()
        );

        $presetFilters = new PresetFiltersDTO(
            format: $params->format,
            city_id: $params->cityId,
            industry_id: $params->industryId
        );

        $presetsQuery = Preset::query();

        foreach (get_object_vars($presetFilters) as $key => $value) {
            $presetsQuery->where("filters->{$key}", $value);
        }

        $presets = $presetsQuery->orderBy('sort_order')->get();

        return new EventSearchResult(
            events: $events,
            presets: $presets,
            meta: $meta,
            facets: $searchResult->getFacetDistribution(),
            facets_stats: $searchResult->getFacetStats()
        );
    }

    private function buildSearchQuery(EventSearchParameters $params): \Laravel\Scout\Builder
    {
        return Event::search($params->query, function (Indexes $meiliSearch, ?string $query, array $options) use ($params) {
            $filter = $this->buildFilters($params);

            return $meiliSearch->search($query, [
                'facets' => ['*'],
                'page' => $params->page,
                'hitsPerPage' => $params->perPage,
                'filter' => $filter,
            ]);
        });
    }

    private function buildFilters(EventSearchParameters $params): array
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
        if ($params->isPriority) {
            $filters[] = "is_priority = true";
        }

        return $filters;
    }
}
