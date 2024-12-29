<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;

class IndustryController extends Controller
{
    public function index()
    {
        return Cache::remember(CacheKeys::ACTIVE_INDUSTRIES->value, 3600, function () {
            $industries = Industry::query()
                ->whereHas('events', function ($query) {
                    $query->active();  // Assuming you have an active scope
                })
                ->withCount('events')
                ->orderBy('sort_order')
                ->get();

            return IndustryResource::collection($industries);
        });
    }

    public function show(Industry $industry)
    {
        return new IndustryResource($industry);
    }

    public function allSlugs()
    {
        return Cache::remember(CacheKeys::INDUSTRIES_SLUGS->value, 3600, function () {
            return Industry::query()->pluck('slug');
        });
    }
}
