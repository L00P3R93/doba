<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PodcastEpisode extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PodcastEpisodeFactory> */
    use Auditable, HasFactory, InteractsWithMedia;

    protected $table = 'podcast_episodes';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hot_or_cold' => 'boolean',
        ];
    }

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('episodes')
            ->useDisk('public')
            ->acceptsFile(fn ($file) => in_array($file->mimeType, [
                'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3',
                'audio/x-wav', 'audio/x-mpeg-3',
            ]));
    }
}
