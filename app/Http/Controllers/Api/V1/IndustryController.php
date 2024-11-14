<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndustryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $industries = Industry::query()
            ->whereHas('events', function ($query) {
                $query->active();  // Assuming you have an active scope
            })
            ->withCount('events')
            ->orderBy('sort_order')
            ->get();

        return IndustryResource::collection($industries);
    }
}
