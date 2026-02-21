<?php

namespace App\Observers;

use App\Models\Artist;

class ArtistObserver
{
    /**
     * Handle the Artist "created" event.
     */
    public function created(Artist $artist): void
    {
        //
    }

    /**
     * Handle the Artist "updated" event.
     */
    public function updated(Artist $artist): void
    {
        //
    }

    /**
     * Handle the Artist "deleted" event.
     */
    public function deleted(Artist $artist): void
    {
        //
    }

    /**
     * Handle the Artist "restored" event.
     */
    public function restored(Artist $artist): void
    {
        //
    }

    /**
     * Handle the Artist "force deleted" event.
     */
    public function forceDeleted(Artist $artist): void
    {
        //
    }
}
