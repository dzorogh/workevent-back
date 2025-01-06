<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class RoutesTest extends TestCase
{
    public function test_all_get_routes_are_accessible(): void
    {
        // Retrieve all routes
        $uris = collect(Route::getRoutes())
            // Only GET API routes
            ->filter(fn (\Illuminate\Routing\Route $route) => in_array('GET', $route->methods()) && str_starts_with($route->uri(), 'api/'))
            ->map(function (\Illuminate\Routing\Route $route) {
                return $route->uri();
            });

        // Iterate over each route
        foreach ($uris as $uri) {
            dump($uri);

            $response = $this->getJson($uri);

            $response
                ->assertStatus(200);
        }
    }
}
