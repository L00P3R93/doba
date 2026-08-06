<?php

namespace Database\Factories;

use App\Enums\AdTargetLevel;
use App\Enums\BannerAdStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BannerAd>
 */
class BannerAdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'user_id' => User::factory(),
            'headline' => fake()->sentence(),
            'cta_text' => fake()->words(2, true),
            'target_level' => fake()->randomElement(AdTargetLevel::cases())->value,
            'base_price_per_impression' => fake()->randomElement([0.20, 0.30, 0.40]),
            'price_per_impression' => fake()->randomFloat(2, 0.20, 0.80),
            'budget' => fake()->randomFloat(2, 100, 10000),
            'max_impressions' => fake()->numberBetween(100, 10000),
            'impressions' => fake()->numberBetween(0, 100),
            'clicks' => fake()->numberBetween(0, 50),
            'status' => BannerAdStatus::Pending->value,
            'is_active' => false,
            'priority' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
        ];
    }
}
