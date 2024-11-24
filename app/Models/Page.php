<?php

namespace App\Models;

use App\Contracts\HasMetadataContract;
use App\Traits\HasMetadata;
use Illuminate\Database\Eloquent\Model;

class Page extends Model implements HasMetadataContract
{
    use HasMetadata;

    protected $fillable = [
        'path',
        'content',
        'title',
    ];
} 