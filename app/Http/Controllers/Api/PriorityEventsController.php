<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PriorityEventsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $events = Event::query()
            ->where('is_priority', true)
            ->orderBy('sort_order', 'desc')
            ->orderBy('start_date', 'asc')
            ->with(['city', 'mainIndustry', 'tags'])
            ->get();

        return EventResource::collection($events);
    }
} 