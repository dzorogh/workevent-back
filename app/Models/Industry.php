<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Industry extends Model
{
    protected $fillable = ['title'];

    public function mainEvents(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
