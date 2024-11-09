<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Industry extends Model
{
    protected $fillable = ['title', 'sort_order'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
