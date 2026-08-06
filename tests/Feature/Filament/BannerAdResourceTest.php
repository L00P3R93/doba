<?php

use App\Models\User;

it('unauthorized users cannot access the banner ads resource', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/admin/banner-ads')
        ->assertStatus(403);
});

it('authorized admins can access the banner ads resource', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    actingAs($user)
        ->get('/admin/banner-ads')
        ->assertStatus(200);
});
