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
        return [
            'transaction_id' => (string) Str::uuid(),
            'transaction_time' => $this->faker->dateTimeBetween('-60 days', '-2 days'),
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
