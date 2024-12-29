<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;
use Illuminate\Http\Resources\AnonymousResourceCollection;

class IndustryController extends Controller
{
    /**
     * Industries
     * 
     * Array of `IndustryResource`
     * 
     * @response array{data: IndustryResource[]}
     */
    public function index()
    {
        return Cache::remember(CacheKeys::ACTIVE_INDUSTRIES->value, 3600, function () {
            $industries = Industry::query()
                ->whereHas('events', function ($query) {
                    $query->active(); 
                })
                ->withCount('events')
                ->orderBy('sort_order')
                ->get();

            return IndustryResource::collection($industries);
        });
    }

    /**
     * Industry
     * 
     * @response IndustryResource
     */
    public function show(Industry $industry)
    {
        return new IndustryResource($industry);
    }

    /**
     * Industry Slugs
     * 
     * Array of industry slugs
     * 
     * @response array{data: string[]}
     */
    public function allSlugs()
    {
        return Cache::remember(CacheKeys::INDUSTRIES_SLUGS->value, 3600, function () {
            return Industry::query()->pluck('slug');
        });
    }
}
