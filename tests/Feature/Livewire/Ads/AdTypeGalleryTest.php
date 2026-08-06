<?php

use App\Livewire\Ads\AdTypeGallery;
use Livewire\Livewire;

it('renders the ad type gallery component', function () {
    Livewire::test(AdTypeGallery::class)
        ->assertStatus(200)
        ->assertSee('Choose Your Ad Targeting Level');
});

it('displays 4 ad type cards', function () {
    Livewire::test(AdTypeGallery::class)
        ->assertStatus(200)
        ->assertSee('General')
        ->assertSee('County')
        ->assertSee('Sub-County')
        ->assertSee('Ward');
});

it('shows indicative pricing for each level', function () {
    Livewire::test(AdTypeGallery::class)
        ->assertStatus(200)
        ->assertSee('Starting from')
        ->assertSee('per impression');
});
