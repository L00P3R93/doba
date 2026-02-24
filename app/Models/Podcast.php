<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Podcast extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PodcastFactory> */
    use Auditable, HasFactory, InteractsWithMedia;

    protected $table = 'podcasts';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(PodcastEpisode::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('covers')
            ->useDisk('public')
            ->acceptsFile(fn ($file) => in_array($file->mimeType, [
                'image/jpeg', 'image/png', 'image/jpg', 'image/gif',
            ]));
    }
}
