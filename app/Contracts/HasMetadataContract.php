<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface HasMetadataContract
{
    public function metadata(): MorphOne;
}
