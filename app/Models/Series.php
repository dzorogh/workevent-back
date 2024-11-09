<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    protected $fillable = ['title'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
