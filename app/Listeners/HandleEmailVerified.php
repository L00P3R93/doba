<?php

namespace App\Listeners;

use App\Jobs\ProcessVerifiedUser;
use Illuminate\Auth\Events\Verified;

class HandleEmailVerified
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        ProcessVerifiedUser::dispatch($event->user);
    }
}
