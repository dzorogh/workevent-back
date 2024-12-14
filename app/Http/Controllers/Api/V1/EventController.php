<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\EventSearchParameters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SearchEventsRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\SearchEventsResource;
use App\Models\Event;
use App\Services\EventSearchService;

class EventController extends Controller
{
    public function __construct(
        private readonly EventSearchService $searchService
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

        return new EventResource($event);
    }
}
