<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\EventFormat;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventFormatResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventFormatController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return EventFormatResource::collection(EventFormat::cases());
    }
} 