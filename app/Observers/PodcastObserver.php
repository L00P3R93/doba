<?php

namespace App\Observers;

use App\Models\Podcast;
use Illuminate\Support\Str;

class PodcastObserver
{
    /**
     * Handle the Album "creating" event.
     */
    public function creating(Podcast $podcast): void
    {
        $podcast->slug = Str::slug($podcast->title);
    }
    /**
     * Handle the Podcast "created" event.
     */
    public function created(Podcast $podcast): void
    {
        //
    }

    /**
     * Handle the Podcast "updated" event.
     */
    public function updated(Podcast $podcast): void
    {
        $podcast->slug = Str::slug($podcast->title);
    }

    /**
     * Handle the Podcast "deleted" event.
     */
    public function deleted(Podcast $podcast): void
    {
        //
    }

    /**
     * Handle the Podcast "restored" event.
     */
    public function restored(Podcast $podcast): void
    {
        //
    }

    /**
     * Handle the Podcast "force deleted" event.
     */
    public function forceDeleted(Podcast $podcast): void
    {
        //
    }
}
