<?php

namespace App\Policies;

use App\Models\Ep;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EpPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_eps');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ep $ep): bool
    {
        return $user->hasPermissionTo('view_ep');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_ep');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ep $ep): bool
    {
        return $user->hasPermissionTo('update_ep');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ep $ep): bool
    {
        return $user->hasPermissionTo('delete_ep');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ep $ep): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ep $ep): bool
    {
        return false;
    }
}
