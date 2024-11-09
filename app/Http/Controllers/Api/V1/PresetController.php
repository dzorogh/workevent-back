<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\EventSearchParameters;
use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\PresetDetailResource;
use App\Http\Resources\Resources\PresetResource;
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

    public function show(Preset $preset)
    {
        $searchResults = $this->searchService->search(
            EventSearchParameters::fromArray($preset->filters)
        );

        return new PresetDetailResource($preset, $searchResults);
    }
}
