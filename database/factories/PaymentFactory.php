<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::query()
            ->whereHas('customerSubscriptions')
            ->with('customerSubscriptions')
            ->inRandomOrder()->first();
        $subscription = $customer?->customerSubscriptions?->random();

        return [
            'customer_id' => $customer?->id,
            'subscription_id' => $subscription?->subscription_id,
            'transaction_id' => (string) Str::uuid(),
            'type' => 'subscription',
            'transaction_time' => $this->faker->dateTimeBetween('-60 days', '-2 days'),
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'short_code' => Str::random(6),
            'bill_ref_no' => Str::random(10),
            'msisdn' => $this->faker->phoneNumber,
            'name' => $this->faker->name,
            'status' => 'completed',
            'created_at' => $this->faker->dateTimeBetween('-60 days', '-2 days'),
            'updated_at' => $this->faker->dateTimeBetween('-60 days', '-2 days'),
        ];
    }
}
