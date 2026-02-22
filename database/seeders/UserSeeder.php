<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $this->command->warn(PHP_EOL.'Creating Admin User...');
        $name = 'Sntaks Admin';
        $nameArr = explode(' ', $name);
        $admin = User::query()->create([
            'account_no' => 'ACC'.str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'name' => $name,
            'username' => Str::lower("{$nameArr[0]}.{$nameArr[1]}"),
            'email' => 'sntaksolutionsltd@gmail.com',
            'phone' => $phone = '0727796831',
            'email_verified_at' => now(),
            'password' => Hash::make("{$nameArr[0]}@{$phone}"),
            'remember_token' => Str::random(10),
            'status' => 'active',
        ]);
        $admin->assignRole('Admin');
        $this->command->info("✓ User {$name} created and assigned to Super Admin role.");

        // Create Non-Admin Users with Profiles
        $this->command->warn(PHP_EOL.'Creating Non-Admin Users with Profiles...');
        $users = $this->withProgressBar(20, fn () => User::factory(1)->create());
        $users->each(function (User $user) {
            $roles = ['Artist', 'Event', 'Studio', 'Record', 'Guest'];
            $user->assignRole($roles[array_rand($roles)]);

            // Create profile for each user
            Profile::factory()->create([
                'user_id' => $user->id,
            ]);

            // Create user subscription for each user
            $subscription = UserSubscription::factory()->create([
                'user_id' => $user->id,
            ]);

            // Create payment for each user subscription
            Payment::factory()->create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->subscription_id,
                'amount' => $subscription->subscription->price,
                'type' => $subscription->subscription->type,
            ]);
        });
        $this->command->info('✓ Other users '.$users->count().' created with profiles, subscriptions and assigned roles.');
    }
}
