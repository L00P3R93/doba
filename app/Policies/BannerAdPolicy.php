<?php

namespace App\Policies;

use App\Models\BannerAd;
use App\Models\User;

class BannerAdPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BannerAd $bannerAd): bool
    {
        return $user->id === $bannerAd->user_id || $user->hasPermissionTo('manage_banner_ads');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BannerAd $bannerAd): bool
    {
        return $user->id === $bannerAd->user_id || $user->hasPermissionTo('manage_banner_ads');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BannerAd $bannerAd): bool
    {
        return $user->id === $bannerAd->user_id || $user->hasPermissionTo('manage_banner_ads');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BannerAd $bannerAd): bool
    {
        return $user->hasPermissionTo('manage_banner_ads');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BannerAd $bannerAd): bool
    {
        return $user->hasPermissionTo('manage_banner_ads');
    }
}
