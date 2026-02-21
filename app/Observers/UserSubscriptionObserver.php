<?php

namespace App\Observers;

use App\Models\UserSubscription;

class UserSubscriptionObserver
{
    /**
     * Handle the UserSubscription "created" event.
     */
    public function created(UserSubscription $userSubscription): void
    {
        //
    }

    /**
     * Handle the UserSubscription "updated" event.
     */
    public function updated(UserSubscription $userSubscription): void
    {
        //
    }

    /**
     * Handle the UserSubscription "deleted" event.
     */
    public function deleted(UserSubscription $userSubscription): void
    {
        //
    }

    /**
     * Handle the UserSubscription "restored" event.
     */
    public function restored(UserSubscription $userSubscription): void
    {
        //
    }

    /**
     * Handle the UserSubscription "force deleted" event.
     */
    public function forceDeleted(UserSubscription $userSubscription): void
    {
        //
    }
}
