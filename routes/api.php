<?php

use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\PresetController;
use App\Http\Controllers\Api\V1\PriorityEventsController;
use App\Http\Controllers\Api\V1\IndustryController;
use App\Http\Controllers\Api\V1\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{event}', [EventController::class, 'show']);
    Route::get('priority-events', [PriorityEventsController::class, 'index']);

    Route::get('presets}', [PresetController::class, 'index']);
    Route::get('presets/{preset}', [PresetController::class, 'show']);

    Route::get('industries', [IndustryController::class, 'index']);

    Route::get('cities', [CityController::class, 'index']);
});
