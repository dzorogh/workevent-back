<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
    ];

    public function topics(): HasMany
    {
        return $this->hasMany(SpeakerTopic::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'speaker_events')
            ->withPivot('description')
            ->withTimestamps();
    }
} 