<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Metadata;
use App\Models\Page;
use App\Models\Preset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Post;
use App\Models\User;
use Meilisearch\Client;
use Kirschbaum\Loop\Facades\Loop;
use Kirschbaum\Loop\Toolkits;
use Kirschbaum\Loop\Tools;

class AppServiceProvider extends ServiceProvider
{
    protected array $morphMap = [
        'event' => Event::class,
        'metadata' => Metadata::class,
        'page' => Page::class,
        'preset' => Preset::class,
        'post' => Post::class,
        'user' => User::class,
    ];


    /**
     * Register any application services.
     */
    public function register(): void
    {
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

        Loop::toolkit(\Kirschbaum\Loop\Filament\FilamentToolkit::make());

    }
}
