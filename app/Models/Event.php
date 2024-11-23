<?php

namespace App\Models;

use App\Contracts\HasMetadataContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Enums\EventFormat;
use Laravel\Scout\Searchable;
use App\Traits\HasMetadata;

class Event extends Model implements HasMedia, HasMetadataContract
{
    use InteractsWithMedia, Searchable, HasMetadata;

    protected $fillable = [
        'title',
        'series_id',
        'start_date',
        'end_date',
        'format',
        'website',
        'phone',
        'email',
        'city_id',
        'industry_id',
        'is_priority',
        'sort_order',
        'description',
        'venue_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_priority' => 'boolean',
        'sort_order' => 'integer',
        'format' => EventFormat::class,
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class)
            ->withPivot('description')
            ->withTimestamps();
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('participation_type')
            ->withTimestamps();
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function tariffs(): HasMany
    {
        return $this->hasMany(Tariff::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile();

        $this->addMediaCollection('gallery');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date->format('Y-m-d'),
            'format' => $this->format->value,
            'format_label' => $this->format->getLabel(),
            'city_id' => $this->city_id,
            'city_title' => $this->city->title,
            'industry_id' => $this->industry_id,
            'industry_title' => $this->industry->title,
            'is_priority' => $this->is_priority,
        ];
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['city', 'industry']);
    }

    public function searchableAs(): string
    {
        return 'events';
    }

    public function scopeActive($query)
    {
        return $query
            ->where('end_date', '>=', now());
    }
}
