<?php

namespace App\Observers;

use App\Models\Single;
use Illuminate\Support\Str;

class SingleObserver
{
    /**
     * Handle the Album "creating" event.
     */
    public function creating(Single $single): void
    {
        $single->slug = Str::slug($single->title);
        if (! $single->user_id) {
            $single->user_id = auth()->user()->id;
        }
    }
    /**
     * Handle the Single "created" event.
     */
    public function created(Single $single): void
    {
        //
    }

    /**
     * Handle the Single "updated" event.
     */
    public function updated(Single $single): void
    {
        $single->slug = Str::slug($single->title);
        if (! $single->user_id) {
            $single->user_id = auth()->user()->id;
        }
    }

    /**
     * Handle the Single "deleted" event.
     */
    public function deleted(Single $single): void
    {
        //
    }

    /**
     * Handle the Single "restored" event.
     */
    public function restored(Single $single): void
    {
        //
    }

    /**
     * Handle the Single "force deleted" event.
     */
    public function forceDeleted(Single $single): void
    {
        //
    }
}
