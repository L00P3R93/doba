<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Single extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\SingleFactory> */
    use Auditable, HasFactory, InteractsWithMedia;

    protected $table = 'singles';

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

    public function registerMediaCollections(): void
    {
        // Audio collection for the single track
        $this->addMediaCollection('singles')
            ->useDisk('public')
            ->singleFile() // Usually a single has only one audio file
            ->acceptsFile(fn ($file) => in_array($file->mimeType, [
                'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3',
                'audio/x-wav', 'audio/x-mpeg-3',
            ]));

        // Cover image collection
        $this->addMediaCollection('covers')
            ->useDisk('public')
            ->singleFile() // Usually only one cover per single
            ->acceptsFile(fn ($file) => in_array($file->mimeType, [
                'image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp',
            ]));
    }

    /**
     * Register media conversions for the cover images
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // For cover images, create thumbnail and medium sizes
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->performOnCollections('covers')
            ->nonQueued(); // Use nonQueued for immediate conversion

        $this->addMediaConversion('medium')
            ->width(500)
            ->height(500)
            ->performOnCollections('covers')
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(1200)
            ->performOnCollections('covers')
            ->nonQueued();
    }

    /**
     * Helper method to get the cover URL with a specific conversion
     */
    public function getCoverUrl(string $conversion = 'medium'): ?string
    {
        $media = $this->getFirstMedia('covers');

        return $media?->getUrl($conversion);
    }

    /**
     * Helper method to get the audio URL
     */
    public function getAudioUrl(): ?string
    {
        $media = $this->getFirstMedia('singles');

        return $media?->getUrl();
    }
}
