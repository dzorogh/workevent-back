<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PresetResource;
use App\Http\Resources\SlugResource;
use App\Models\Preset;
use App\Services\EventSearchService;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;

class PresetController extends Controller
{
    public function __construct(
        private readonly EventSearchService $searchService
    ) {}

    /**
     * Presets
     * 
     * Array of `PresetResource`
     * 
     * @response array{data: PresetResource[]}
     */
    public function index()
    {
        return Cache::remember(CacheKeys::ACTIVE_PRESETS->value, 3600, function () {
            $presets = Preset::where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            return PresetResource::collection($presets);
        });
    }

    /**
     * Preset Slugs
     * 
     * Array of preset slugs
     * 
     * @response array{data: string[]}
     */
    public function allSlugs()
    {
        return Cache::remember(CacheKeys::ACTIVE_PRESETS_SLUGS->value, 3600, function () {
            $presets = Preset::where('is_active', true)
                ->pluck('slug');

            return SlugResource::collection($presets);
        });
    }

    /**
     * Preset
     * 
     * @response PresetResource
     */
    public function show(Preset $preset)
    {
        $preset->load('metadata');
        return new PresetResource($preset);
    }
}
