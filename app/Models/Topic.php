<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    protected $fillable = ['title'];

    public function speakers(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }
}
