<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\EventSearchParameters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SearchEventsRequest;
use App\Http\Resources\SearchEventsResource;
use App\Services\EventSearchService;

class EventController extends Controller
{
    public function __construct(
        private readonly EventSearchService $searchService
    ) {}

    public function index(SearchEventsRequest $request)
    {
        $result = $this->searchService->search(EventSearchParameters::fromArray($request->validated()));

        return new SearchEventsResource($result['events'], $result['facets']);
    }
}
