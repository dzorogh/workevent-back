<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PresetResource;
use App\Http\Resources\SlugResource;
use App\Models\Preset;
use App\Services\EventSearchService;

class PresetController extends Controller
{
    public function __construct(
        private readonly EventSearchService $searchService
    ) {}

    public function index()
    {
        return PresetResource::collection(
            Preset::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
        );
    }

    public function allSlugs()
    {
        return SlugResource::collection(
            Preset::where('is_active', true)
                ->pluck('slug')
        );
    }

    public function show(Preset $preset)
    {
        $preset->load('metadata');
        return new PresetResource($preset);
    }
}
