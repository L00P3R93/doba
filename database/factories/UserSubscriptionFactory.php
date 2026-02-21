<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSubscription>
 */
class UserSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subscription = Subscription::query()->inRandomOrder()->first();
        return [
            'subscription_id' => $subscription->id,
            'start_date' => $startDate = fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->dateTimeBetween($startDate, '+1 year'),
            'status' => fake()->randomElement(['active', 'expired', 'cancelled']),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
