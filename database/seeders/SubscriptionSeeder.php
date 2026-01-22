<?php

namespace Database\Seeders;

use App\Models\CustomerSubscription;
use App\Models\Subscription;

class SubscriptionSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding subscriptions');

        $subscriptions = $this->withProgressBar(10, function () {
            return Subscription::factory()->create();
        });

        $this->command->info("Created {$subscriptions->count()} subscriptions");

        $this->command->info('Seeding customer subscriptions');
        $customerSubscriptions = $this->withProgressBar($subscriptions->count(), function () {
            return CustomerSubscription::factory()->create();
        });
        $this->command->info("Created {$customerSubscriptions->count()} customer subscriptions");
    }
}
