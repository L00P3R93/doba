<?php

namespace App\Observers;

use App\Models\Song;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SongObserver
{
    /**
     * Handle the Song "creating" event.
     */
    public function creating(Song $song): void
    {
        $song->slug = Str::slug($song->title);
    }
    /**
     * Handle the Song "created" event.
     */
    public function created(Song $song): void
    {
        //
    }

    /**
     * Handle the Song "updated" event.
     */
    public function updated(Song $song): void
    {
        $song->slug = Str::slug($song->title);
    }

    /**
     * Handle the Song "deleted" event.
     */
    public function deleted(Song $song): void
    {
        //
    }

    /**
     * Handle the Song "restored" event.
     */
    public function restored(Song $song): void
    {
        //
    }

    /**
     * Handle the Song "force deleted" event.
     */
    public function forceDeleted(Song $song): void
    {
        //
    }
}
