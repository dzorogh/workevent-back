<?php

namespace App\Traits;

use App\Models\Metadata;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasMetadata
{
    public function metadata(): MorphOne
    {
        return $this->morphOne(Metadata::class, 'metadatable');
    }
}
