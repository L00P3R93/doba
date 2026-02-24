<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ep extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\EpFactory> */
    use Auditable, HasFactory, InteractsWithMedia;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hot_or_cold' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ep_songs(): HasMany
    {
        return $this->hasMany(EpSong::class);
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
