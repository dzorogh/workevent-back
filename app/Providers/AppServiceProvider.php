<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Metadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Meilisearch\Client;

class AppServiceProvider extends ServiceProvider
{
    protected array $morphMap = [
        'event' => Event::class,
        'metadata' => Metadata::class,
        // Добавьте другие модели здесь
    ];


    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');
        request()->server->set('HTTPS', request()->header('X-Forwarded-Proto', 'https') == 'https' ? 'on' : 'off');

        Relation::enforceMorphMap($this->morphMap);

        Model::preventLazyLoading();

    }
}
