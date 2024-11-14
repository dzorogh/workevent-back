<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $cities = Cache::remember('active_cities', 3600, function () {
            return City::query()
                ->whereHas('events', function ($query) {
                    $query->active();
                })
                ->withCount('events')
                ->orderBy('title')
                ->get();
        });

        return CityResource::collection($cities);
    }
}
