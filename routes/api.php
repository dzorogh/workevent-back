<?php

use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\FilterPresetController;
use App\Http\Controllers\Api\V1\PriorityEventsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{event}', [EventController::class, 'show']);
    Route::get('priority-events', [PriorityEventsController::class, 'index']);
    Route::get('filter-presets', [FilterPresetController::class, 'index']);
});
