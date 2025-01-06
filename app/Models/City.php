<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;
use Illuminate\Support\Facades\Artisan;

class City extends Model
{
    protected $fillable = ['title'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget(CacheKeys::ACTIVE_CITIES->value);
            Artisan::queue('nextjs:revalidate');
        });
    }
}
