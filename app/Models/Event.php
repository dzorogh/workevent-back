<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    protected $fillable = [
        'series_id',
        'title',
        'start_date',
        'end_date',
        'format',
        'website',
        'city_id',
        'main_industry_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(EventSeries::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function mainIndustry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(EventTag::class, 'event_tag', 'tag_id', 'event_id');
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class, 'speaker_events')
            ->withPivot('description')
            ->withTimestamps();
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_event')
            ->withPivot('participation_type')
            ->withTimestamps();
    }
}
