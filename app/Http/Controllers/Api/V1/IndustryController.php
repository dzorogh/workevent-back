<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;
use App\Http\Resources\SlugResource;

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
     * Preset Slugs
     *
     * Array of preset slugs
     *
     * @response array{data: SlugResource[]}
     */
    public function allSlugs()
    {
        return Cache::remember(CacheKeys::INDUSTRIES_SLUGS->value, 3600, function () {
            $industries = Industry::pluck('slug');

            return SlugResource::collection($industries);
        });
    }
}
