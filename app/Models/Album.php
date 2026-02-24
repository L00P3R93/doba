<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Album extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\AlbumFactory> */
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

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
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
