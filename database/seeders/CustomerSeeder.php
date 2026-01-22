<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Profile;

class CustomerSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding customers');

        $customers = $this->withProgressBar(10, function () {
            return Customer::factory()->create();
        });

        $this->command->info("Created {$customers->count()} customers");

        $this->command->info('Seeding profiles');
        $profiles = $this->withProgressBar($customers->count(), function () {
            return Profile::factory()->create();
        });
        $this->command->info("Created {$profiles->count()} profiles");
    }
}
