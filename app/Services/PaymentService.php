<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function getAllPayments(): Collection
    {
        return Payment::all();
    }

    /**
     * @throws \Exception
     */
    public function createPayment(array $data): Payment
    {
        try {
            $payment = Payment::query()->create($data);
            if ($payment) {
                $billRefNo = $payment->bill_ref_no;
                // TODO: Extract Customer & Subscritption Info from bill_ref_no
                // TODO: Get Customer & Subscription Info
                // TODO: Update Customer & Subscription
                // TODO: Update Customer Subscription Status
            }

            return $payment;
        } catch (\Exception $e) {
            Log::error('Error creating payment: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getPaymentById($id): ?Payment
    {
        try {
            return Payment::query()->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error getting payment by id: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function updatePayment(Payment $payment, array $data): Payment
    {
        try {
            $payment->update($data);

            return $payment->fresh();
        } catch (\Exception $e) {
            Log::error('Error updating payment: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function deletePayment(Payment $payment): bool
    {
        try {
            return $payment->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting payment: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getPaymentsByCustomer($customerId): Collection
    {
        try {
            return Payment::query()->where('customer_id', $customerId)->get();
        } catch (\Exception $e) {
            Log::error('Error getting payments by customer: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getPaymentsBySubscription($subscriptionId): Collection
    {
        try {
            return Payment::query()->where('subscription_id', $subscriptionId)->get();
        } catch (\Exception $e) {
            Log::error('Error getting payments by subscription: '.$e->getMessage());
            throw $e;
        }
    }
}
