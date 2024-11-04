<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventTag extends Model
{
    protected $fillable = ['title'];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_tag', 'event_id', 'tag_id');
    }
}
