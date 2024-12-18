<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\EventSearchParameters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SearchEventsRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\IdResource;
use App\Http\Resources\SearchEventsResource;
use App\Models\Event;
use App\Services\EventSearchService;
use App\Services\PresetService;
use App\DTOs\PresetFiltersDTO;
use Illuminate\Support\Collection;
use App\Http\Resources\PresetResource;

class EventController extends Controller
{
    public function __construct(
        private readonly EventSearchService $searchService,
        private readonly PresetService $presetService
    ) {}

    public function index(SearchEventsRequest $request)
    {
        $params = EventSearchParameters::fromArray($request->validated());

        if (!$params->dateFrom) {
            $params->setDateFrom(now()->getTimestamp());
        }

        $result = $this->searchService->search($params);

        return new SearchEventsResource($result);
    }

    public function allIds()
    {
        $ids = Event::whereDate('start_date', '>=', now()->format('Y-m-d'))
            ->pluck('id');

        return IdResource::collection($ids);
    }

    public function show(Event $event)
    {
        $event->load([
            'city',
            'industry',
            'media',
            'metadata',
            'tariffs',
            'tags',
        ]);

        $presetFilters = new PresetFiltersDTO(
            format: $event->format,
            city_id: $event->city_id,
            industry_id: $event->industry_id
        );

        $presets = $this->presetService->getPresetsWithOptionalFilters($presetFilters);

        return (new EventResource($event))->additional(['presets' => PresetResource::collection($presets)]);
    }
}
