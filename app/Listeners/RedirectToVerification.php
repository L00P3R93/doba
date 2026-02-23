<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;

class RedirectToVerification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(Registered $event): void
    {
        if (!$event->user->hasVerifiedEmail()) {
            // Store a session flag to redirect to verification after login
            Session::flash('redirect_to_verification', true);
            Session::flash('verification_message', 'Please verify your email address to continue.');
        }
    }
}
