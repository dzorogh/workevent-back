<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    protected $fillable = [
        'title',
        'inn',
    ];

    protected $casts = [
        'event_participations' => 'array',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'company_event')
            ->withPivot('participation_type')
            ->withTimestamps();
    }

    protected static function booted()
    {
        static::saved(function ($company) {
            if (isset($company->event_participations)) {
                $events = collect($company->event_participations)->mapWithKeys(function ($item) {
                    return [$item['event_id'] => ['participation_type' => $item['participation_type']]];
                })->toArray();

                $company->events()->sync($events);
            }
        });
    }
} 