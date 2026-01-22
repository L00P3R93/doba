<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    public function getAllSubscriptions(): \Illuminate\Database\Eloquent\Collection
    {
        return Subscription::all();
    }

    /**
     * @throws \Exception
     */
    public function createSubscription(array $data): Subscription
    {
        try {
            return Subscription::query()->create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create subscription: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getSubscriptionById($id): ?Subscription
    {
        try {
            return Subscription::query()->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Subscription Not Found: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function updateSubscription(Subscription $subscription, array $data): Subscription
    {
        try {
            $subscription->update($data);
            return $subscription->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update subscription: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteSubscription(Subscription $subscription): bool
    {
        try {
            $subscription->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete subscription: '.$e->getMessage());
            throw $e;
        }
    }

}
