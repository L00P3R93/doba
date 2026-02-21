<?php

namespace App\Services;

use App\Models\UserSubscription;
use Illuminate\Support\Facades\Log;

class UserSubscriptionService
{
    public function getAllUserSubscriptions(): \Illuminate\Database\Eloquent\Collection
    {
        return UserSubscription::all();
    }

    /**
     * @throws \Exception
     */
    public function createUserSubscription(array $data): UserSubscription
    {
        try {
            return UserSubscription::query()->create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create user subscription: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getUserSubscriptionById($id): ?UserSubscription
    {
        try {
            return UserSubscription::query()->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('User Subscription Not Found: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function updateUserSubscription(UserSubscription $userSubscription, array $data): UserSubscription
    {
        try {
            $userSubscription->update($data);

            return $userSubscription->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update user subscription: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteUserSubscription(UserSubscription $userSubscription): bool
    {
        try {
            $userSubscription->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete user subscription: '.$e->getMessage());
            throw $e;
        }
    }
}
