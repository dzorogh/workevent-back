<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SearchEventsRequest;
use App\Http\Resources\SearchEventsResource;
use App\Services\EventSearchService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        private readonly EventSearchService $searchService
    ) {}

    public function index(SearchEventsRequest $request)
    {
        $result = $this->searchService->search($request);

        return new SearchEventsResource($result['events'], $result['facets']);
    }
}
