<?php

namespace App\Services;

use App\Models\CustomerSubscription;
use Illuminate\Support\Facades\Log;

class CustomerSubscriptionService
{
    public function getAllCustomerSubscriptions(): \Illuminate\Database\Eloquent\Collection
    {
        return CustomerSubscription::all();
    }

    /**
     * @throws \Exception
     */
    public function createCustomerSubscription(array $data): CustomerSubscription
    {
        try {
            return CustomerSubscription::query()->create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create customer subscription: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getCustomerSubscriptionById($id): ?CustomerSubscription
    {
        try {
            return CustomerSubscription::query()->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Customer Subscription Not Found: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function updateCustomerSubscription(CustomerSubscription $customerSubscription, array $data): CustomerSubscription
    {
        try {
            $customerSubscription->update($data);

            return $customerSubscription->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update customer subscription: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteCustomerSubscription(CustomerSubscription $customerSubscription): bool
    {
        try {
            $customerSubscription->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete customer subscription: '.$e->getMessage());
            throw $e;
        }
    }
}
