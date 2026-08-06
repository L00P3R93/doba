<?php

namespace Database\Factories;

use App\Models\County;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCounty>
 */
class SubCountyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uid' => fake()->unique()->numerify('###'),
            'name' => fake()->city(),
            'county_id' => County::factory(),
        ];
    }
}
