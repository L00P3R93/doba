<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Song extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\SongFactory> */
    use Auditable, HasFactory, InteractsWithMedia;

    protected $table = 'songs';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hot_or_cold' => 'boolean',
        ];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
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
