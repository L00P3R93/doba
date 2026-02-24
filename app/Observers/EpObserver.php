<?php

namespace App\Observers;

use App\Models\Ep;
use Illuminate\Support\Str;

class EpObserver
{
    /**
     * Handle the Album "creating" event.
     */
    public function creating(Ep $ep): void
    {
        $ep->slug = Str::slug($ep->title);
        if (! $ep->user_id) {
            $ep->user_id = auth()->user()->id;
        }
    }
    /**
     * Handle the Ep "created" event.
     */
    public function created(Ep $ep): void
    {
        //
    }

    /**
     * Handle the Ep "updated" event.
     */
    public function updated(Ep $ep): void
    {
        $ep->slug = Str::slug($ep->title);
        if (! $ep->user_id) {
            $ep->user_id = auth()->user()->id;
        }
    }

    /**
     * Handle the Ep "deleted" event.
     */
    public function deleted(Ep $ep): void
    {
        //
    }

    /**
     * Handle the Ep "restored" event.
     */
    public function restored(Ep $ep): void
    {
        //
    }

    /**
     * Handle the Ep "force deleted" event.
     */
    public function forceDeleted(Ep $ep): void
    {
        //
    }
}
