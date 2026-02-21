<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSubscription>
 */
class CustomerSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::query()->inRandomOrder()->first();
        $subscription = Subscription::query()->inRandomOrder()->first();
        return [
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'start_date' => $startDate = fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->dateTimeBetween($startDate, '+1 year'),
            'status' => fake()->randomElement(['active', 'expired', 'cancelled']),
            'downloads_used' => fake()->numberBetween(0, 100),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
