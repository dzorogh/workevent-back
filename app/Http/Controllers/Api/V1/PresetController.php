<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PresetResource;
use App\Models\Preset;

class PresetController extends Controller
{
    public function index()
    {
        return PresetResource::collection(
            Preset::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
        );
    }
}
