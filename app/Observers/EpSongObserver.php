<?php

namespace App\Observers;

use App\Models\EpSong;
use Illuminate\Support\Str;

class EpSongObserver
{
    /**
     * Handle the Album "creating" event.
     */
    public function creating(EpSong $epSong): void
    {
        $epSong->slug = Str::slug($epSong->title);
    }
    /**
     * Handle the EpSong "created" event.
     */
    public function created(EpSong $epSong): void
    {
        //
    }

    /**
     * Handle the EpSong "updated" event.
     */
    public function updated(EpSong $epSong): void
    {
        $epSong->slug = Str::slug($epSong->title);
    }

    /**
     * Handle the EpSong "deleted" event.
     */
    public function deleted(EpSong $epSong): void
    {
        //
    }

    /**
     * Handle the EpSong "restored" event.
     */
    public function restored(EpSong $epSong): void
    {
        //
    }

    /**
     * Handle the EpSong "force deleted" event.
     */
    public function forceDeleted(EpSong $epSong): void
    {
        //
    }
}
