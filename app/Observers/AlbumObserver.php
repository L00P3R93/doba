<?php

namespace App\Observers;

use App\Models\Album;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AlbumObserver
{
    /**
     * Handle the Album "creating" event.
     */
    public function creating(Album $album): void
    {
        $album->slug = Str::slug($album->title);
        $album->is_active = true;
        $album->hot_or_cold = true;
        if (! $album->user_id) {
            $album->user_id = auth()->user()->id;
        }
    }

    /**
     * Handle the Album "created" event.
     */
    public function created(Album $album): void
    {
        //
    }

    /**
     * Handle the Album "updated" event.
     */
    public function updated(Album $album): void
    {
        $album->slug = Str::slug($album->title);
    }

    /**
     * Handle the Album "deleted" event.
     */
    public function deleted(Album $album): void
    {
        //
    }

    /**
     * Handle the Album "restored" event.
     */
    public function restored(Album $album): void
    {
        //
    }

    /**
     * Handle the Album "force deleted" event.
     */
    public function forceDeleted(Album $album): void
    {
        //
    }
}
