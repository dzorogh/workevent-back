<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FilterPreset extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'filters',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'filters' => 'array',
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
