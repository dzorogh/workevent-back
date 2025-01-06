<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Industry extends Model
{
    protected $fillable = ['title', 'sort_order', 'slug'];

    protected $touches = ['events'];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget(CacheKeys::ACTIVE_INDUSTRIES->value);
            Artisan::queue('nextjs:revalidate');
        });

        static::deleted(function () {
            Cache::forget(CacheKeys::ACTIVE_INDUSTRIES->value);
            Artisan::queue('nextjs:revalidate');
        });

        static::created(function (Industry $industry) {
            $industry->slug = Str::slug($industry->title);
            $industry->save();
        });
    }
}

