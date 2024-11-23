<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndustryResource;
use App\Models\City;
use App\Models\Industry;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class IndustryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $industries = Cache::remember('active_industries', 3600, function () {
            return Industry::query()
                ->whereHas('events', function ($query) {
                    $query->active();  // Assuming you have an active scope
                })
                ->withCount('events')
                ->orderBy('sort_order')
                ->get();
        });

        return IndustryResource::collection($industries);
    }
}
