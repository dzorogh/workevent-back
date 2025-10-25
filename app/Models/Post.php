<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasMetadata;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Post extends Model implements HasMedia
{
    use HasMetadata, InteractsWithMedia, HasTimestamps;

    protected $fillable = [
        'title',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // set the user id when creating a post
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (!Auth::check()) {
                throw new Exception('User must be authenticated to create a post.');
            }

            $model->user_id = Auth::id();
        });

        static::saved(function (Post $post) {
            Artisan::queue('nextjs:revalidate');
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile();
    }
}
