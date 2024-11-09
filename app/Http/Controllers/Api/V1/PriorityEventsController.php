<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PriorityEventsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $events = Event::query()
            ->where('is_priority', true)
            ->orderBy('sort_order', 'desc')
            ->orderBy('start_date', 'asc')
            ->with(['city', 'industry', 'tags'])
            ->get();

        return EventResource::collection($events);
    }
}
