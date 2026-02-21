<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
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
                $this->processPayment($payment);
            }

            return $payment;
        } catch (\Exception $e) {
            Log::error('Error creating payment: '.$e->getMessage());
            throw $e;
        }
    }

    private function processPayment(Payment $payment): void
    {
        $billRefNo = $payment->bill_ref_no;

        // Expected Format: DP-ACCCOUNTNO-SUBSCRIPTIONID (e.g. DP-1234-456)
        $parts = explode('-', $billRefNo);

        if (count($parts) !== 3) {
            Log::error("Invalid bill_ref_no format: {$billRefNo}");

            return;
        }

        $accountNo = $parts[1];
        $subscriptionId = $parts[2];

        $user = User::query()->where('account_no', $accountNo);
        $subscription = Subscription::find($subscriptionId);

        if (! $user || ! $subscription) {
            Log::error("User or Subscription not found for payment: {$payment->id}");

            return;
        }

        // Map subscription type to role
        $roleMapping = [
            'artist' => 'Artist',
            'event' => 'Event',
            'studio' => 'Studio',
            'record' => 'Record',
        ];

        $targetRole = $roleMapping[$subscription->type] ?? null;

        if (! $targetRole) {
            Log::error("Invalid subscription type: {$subscription->type}");

            return;
        }

        // Remove Guest role and assign new role
        $user->removeRole('Guest');
        $user->assignRole($targetRole);

        // Create customer subscription record
        \App\Models\CustomerSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'payment_id' => $payment->id,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays($subscription->duration_days),
        ]);

        Log::info("User {$user->id} upgraded from Guest to {$targetRole}");
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
