<?php

namespace App\Models;

use App\Contracts\HasMetadataContract;
use App\Traits\HasMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Page extends Model implements HasMetadataContract
{
    use HasMetadata;

    protected $fillable = [
        'path',
        'content',
        'title',
    ];


    public static function boot()
    {
        parent::boot();

        static::saved(function (Page $page) {
            Artisan::queue('nextjs:revalidate');
        });
    }
}
