<?php

use App\Enums\AdTargetLevel;
use App\Livewire\Ads\CreateBannerAd;
use App\Models\User;
use Livewire\Livewire;

it('redirects guest users to login', function () {
    $response = $this->get(route('ads.create', ['level' => 'general']));

    $response->assertRedirect(route('login'));
});

it('allows authenticated users to access the create page', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CreateBannerAd::class, ['level' => 'general'])
        ->assertStatus(200);
});

it('displays the correct level based on route parameter', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CreateBannerAd::class, ['level' => 'ward'])
        ->assertSet('level', AdTargetLevel::Ward);
});
