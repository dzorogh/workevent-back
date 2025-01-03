<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;
use Illuminate\Support\Str;

class Industry extends Model
{
    protected $fillable = ['title', 'sort_order', 'slug'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget(CacheKeys::ACTIVE_INDUSTRIES->value);
        });

        static::deleted(function () {
            Cache::forget(CacheKeys::ACTIVE_INDUSTRIES->value);
        }); 

        static::created(function (Industry $industry) {
            $industry->slug = Str::slug($industry->title);
            $industry->save();
        });
    }
}

