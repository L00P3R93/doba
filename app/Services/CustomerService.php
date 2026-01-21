<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    public function getAllCustomers(): \Illuminate\Database\Eloquent\Collection
    {
        return Customer::all();
    }

    /**
     * @throws \Exception
     */
    public function createCustomer(array $data): Customer
    {
        try {
            return Customer::query()->create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create customer: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getCustomerById($id): ?Customer
    {
        try {
            return Customer::query()->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Customer Not Found: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function updateCustomer(Customer $customer, array $data): Customer
    {
        try {
            $customer->update($data);
            return $customer->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update customer: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteCustomer(Customer $customer): bool
    {
        try {
            $customer->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete customer: '.$e->getMessage());
            throw $e;
        }
    }
}
