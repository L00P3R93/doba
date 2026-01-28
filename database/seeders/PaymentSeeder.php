<?php

namespace Database\Seeders;

use App\Models\Payment;

class PaymentSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding payments...');

        $payments = $this->withProgressBar(5, function () {
            return Payment::factory()->create();
        });

        $this->command->info("Seeded {$payments->count()} payments");
    }
}
