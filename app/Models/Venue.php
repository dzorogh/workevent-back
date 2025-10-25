<?php

namespace App\Models;

use Database\Factories\VenueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    /** @use HasFactory<VenueFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'address',
        'website',
        'phone',
        'email',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
