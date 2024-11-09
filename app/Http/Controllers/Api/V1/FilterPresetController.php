<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilterPresetResource;
use App\Models\FilterPreset;

class FilterPresetController extends Controller
{
    public function index()
    {
        return FilterPresetResource::collection(
            FilterPreset::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
        );
    }
}
