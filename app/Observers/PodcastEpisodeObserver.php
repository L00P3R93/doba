<?php

namespace App\Observers;

use App\Models\PodcastEpisode;
use Illuminate\Support\Str;

class PodcastEpisodeObserver
{
    /**
     * Handle the Album "creating" event.
     */
    public function creating(PodcastEpisode $podcastEpisode): void
    {
        $podcastEpisode->slug = Str::slug($podcastEpisode->title);
    }
    /**
     * Handle the PodcastEpisode "created" event.
     */
    public function created(PodcastEpisode $podcastEpisode): void
    {
        //
    }

    /**
     * Handle the PodcastEpisode "updated" event.
     */
    public function updated(PodcastEpisode $podcastEpisode): void
    {
        $podcastEpisode->slug = Str::slug($podcastEpisode->title);
    }

    /**
     * Handle the PodcastEpisode "deleted" event.
     */
    public function deleted(PodcastEpisode $podcastEpisode): void
    {
        //
    }

    /**
     * Handle the PodcastEpisode "restored" event.
     */
    public function restored(PodcastEpisode $podcastEpisode): void
    {
        //
    }

    /**
     * Handle the PodcastEpisode "force deleted" event.
     */
    public function forceDeleted(PodcastEpisode $podcastEpisode): void
    {
        //
    }
}
