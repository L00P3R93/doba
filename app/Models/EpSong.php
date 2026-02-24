<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EpSong extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\EpSongFactory> */
    use Auditable, HasFactory, InteractsWithMedia;

    protected $table = 'ep_songs';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hot_or_cold' => 'boolean',
        ];
    }

    public function ep(): BelongsTo
    {
        return $this->belongsTo(Ep::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('songs')
            ->useDisk('public')
            ->acceptsFile(fn ($file) => in_array($file->mimeType, [
                'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3',
                'audio/x-wav', 'audio/x-mpeg-3',
            ]));
    }
}
