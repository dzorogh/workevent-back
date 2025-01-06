<?php

use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\MetadataController;
use App\Http\Controllers\Api\V1\PresetController;
use App\Http\Controllers\Api\V1\IndustryController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\EventFormatController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/ids', [EventController::class, 'allIds']);
    Route::get('events/{event}', [EventController::class, 'show']);

    Route::get('presets', [PresetController::class, 'index']);
    Route::get('presets/slugs', [PresetController::class, 'allSlugs']);
    Route::get('presets/{preset:slug}', [PresetController::class, 'show']);

    Route::get('industries', [IndustryController::class, 'index']);
    Route::get('industries/slugs', [IndustryController::class, 'allSlugs']);
    Route::get('industries/{industry:slug}', [IndustryController::class, 'show']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{post}', [PostController::class, 'show']);

    Route::get('cities', [CityController::class, 'index']);

    Route::get('metadata', [MetadataController::class, 'show']);

    Route::get('event-formats', [EventFormatController::class, 'index']);

    Route::get('pages', [PageController::class, 'show']);

});
