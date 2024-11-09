<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Metadata extends Model
{
    protected $guarded = ['id'];

    public function metadatable(): MorphTo
    {
        return $this->morphTo();
    }
}
