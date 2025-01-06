<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class EventIndustry extends Model
{
    protected $fillable = ['event_id', 'industry_id'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }
}
