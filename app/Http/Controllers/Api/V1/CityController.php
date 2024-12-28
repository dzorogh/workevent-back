<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;

class CityController extends Controller
{
    public function index()
    {
        return Cache::remember(CacheKeys::ACTIVE_CITIES->value, 3600, function () {
            $cities = City::query()
                ->whereHas('events', function ($query) {
                    $query->active();
                })
                ->withCount('events')
                ->orderBy('title')
                ->get();

            return CityResource::collection($cities);
        });
    }
}
