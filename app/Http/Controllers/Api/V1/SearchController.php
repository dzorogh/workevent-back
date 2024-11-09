<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchEventsResource;
use App\Http\Requests\Api\V1\SearchEventsRequest;
use App\Services\EventSearchService;

class SearchController extends Controller
{
    public function __construct(
        private readonly EventSearchService $searchService
    ) {}

    public function events(SearchEventsRequest $request)
    {
        $result = $this->searchService->search($request->validated());

        return new SearchEventsResource($result['events'], $result['facets']);
    }
}
