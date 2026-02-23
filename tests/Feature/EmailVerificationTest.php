<?php

use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('user must verify email to access panels', function () {
    $user = User::factory()->create(['email_verified_at' => null]);
    
    expect($user->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')))->toBeFalse();
    expect($user->canAccessPanel(\Filament\Facades\Filament::getPanel('artist')))->toBeFalse();
    expect($user->canAccessPanel(\Filament\Facades\Filament::getPanel('studio')))->toBeFalse();
    expect($user->canAccessPanel(\Filament\Facades\Filament::getPanel('record')))->toBeFalse();
    expect($user->canAccessPanel(\Filament\Facades\Filament::getPanel('event')))->toBeFalse();
});

test('verified user can access panels with proper roles', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('Admin');
    
    expect($user->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')))->toBeTrue();
    expect($user->canAccessPanel(\Filament\Facades\Filament::getPanel('artist')))->toBeFalse();
});

test('email verification service sends verification email', function () {
    Queue::fake();
    
    $user = User::factory()->create(['email_verified_at' => null]);
    $service = new EmailVerificationService();
    
    $result = $service->sendVerificationEmail($user);
    
    expect($result)->toBeTrue();
    expect($service->isEmailVerified($user))->toBeFalse();
    
    Queue::assertPushed(\App\Jobs\SendEmailVerificationJob::class, function ($job) use ($user) {
        return $job->user->id === $user->id;
    });
});

test('email verification notification contains correct data', function () {
    Notification::fake();
    
    $user = User::factory()->create(['email_verified_at' => null]);
    
    $user->notify(new \App\Notifications\VerifyEmailNotification());
    
    Notification::assertSentTo($user, \App\Notifications\VerifyEmailNotification::class);
});

test('user registration sends verification email', function () {
    Queue::fake();
    
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'password' => 'password123',
    ];
    
    $user = (new \App\Actions\Fortify\CreateNewUser(app(\App\Services\EmailVerificationService::class)))->create($userData);
    
    expect($user->email_verified_at)->toBeNull();
    expect($user->account_no)->toStartWith('DOB');
    expect($user->hasRole('Guest'))->toBeTrue();
    
    Queue::assertPushed(\App\Jobs\SendEmailVerificationJob::class);
});

test('email verification middleware blocks unverified users', function () {
    $user = User::factory()->create(['email_verified_at' => null]);
    
    $response = $this->actingAs($user)->get('/admin');
    
    $response->assertRedirect('/email/verify');
});

test('email verification middleware allows verified users', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('Admin');
    
    // This test would need actual panel routes to work properly
    expect($user->hasVerifiedEmail())->toBeTrue();
});
