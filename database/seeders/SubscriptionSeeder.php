<?php

namespace Database\Seeders;

use App\Models\Subscription;

class SubscriptionSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding subscriptions');

        $index = 0;
        $subscriptions = $this->withProgressBar(4, function () use (&$index) {
            $subscriptionsData = [
                ['name' => 'Event', 'type' => 'event', 'price' => 499, 'duration_days' => 365],
                ['name' => 'Artist + Videos', 'type' => 'artist', 'price' => 1499, 'duration_days' => 365],
                ['name' => 'Studio', 'type' => 'studio', 'price' => 4999, 'duration_days' => 365],
                ['name' => 'Record Label', 'type' => 'record', 'price' => 14999, 'duration_days' => 365],
            ];
            $data = $subscriptionsData[$index];
            $index++;

            return Subscription::factory()->create([
                'name' => $data['name'],
                'type' => $data['type'],
                'price' => $data['price'],
                'duration_days' => $data['duration_days'],
                'is_active' => true,
            ]);
        });

        $this->command->info("Created {$subscriptions->count()} subscriptions");
    }
}
