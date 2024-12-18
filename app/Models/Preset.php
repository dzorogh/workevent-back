<?php

namespace App\Models;

use App\Contracts\HasMetadataContract;
use App\Traits\HasMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Casts\PresetFilters;

class Preset extends Model implements HasMetadataContract
{
    use HasMetadata;

    protected $fillable = [
        'title',
        'slug',
        'filters',
        'is_active',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'filters' => PresetFilters::class,
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
