<?php

namespace Database\Factories;

use App\Models\County;
use App\Models\SubCounty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ward>
 */
class WardFactory extends Factory
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
            'name' => fake()->streetName(),
            'county_id' => County::factory(),
            'sub_county_id' => SubCounty::factory(),
            'population' => fake()->numberBetween(1000, 100000),
        ];
    }
}
