<?php

use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\PresetController;
use App\Http\Controllers\Api\V1\PriorityEventsController;
use App\Http\Controllers\Api\V1\IndustryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{event}', [EventController::class, 'show']);

    Route::get('priority-events', [PriorityEventsController::class, 'index']);
    Route::get('filter-presets', [PresetController::class, 'index']);

    Route::get('industries', [IndustryController::class, 'index']);
});
