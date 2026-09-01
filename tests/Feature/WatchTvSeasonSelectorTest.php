<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('displays the season and episode selector panel', function () {
    Http::fake(function ($request) {
        $url = $request->url();

        if (str_contains($url, '/season/')) {
            return Http::response([
                'episodes' => [
                    ['episode_number' => 1, 'name' => 'Pilot', 'overview' => 'First episode.'],
                ],
            ], 200);
        }

        return Http::response([
            'name' => 'Test Show',
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1'],
                ['season_number' => 2, 'name' => 'Season 2'],
            ],
            'backdrop_path' => '/backdrop.jpg',
            'poster_path' => '/poster.jpg',
        ], 200);
    });

    $response = $this->get('/watch/tv/123/1/1');

    $response->assertOk();
    $response->assertSee('Episodes');
    $response->assertSee('S01');
    $response->assertSee('S02');
});

it('highlights the active season', function () {
    Http::fake(function ($request) {
        $url = $request->url();

        if (str_contains($url, '/season/')) {
            return Http::response([
                'episodes' => [
                    ['episode_number' => 1, 'name' => 'Pilot', 'overview' => 'First episode.'],
                ],
            ], 200);
        }

        return Http::response([
            'name' => 'Test Show',
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1'],
                ['season_number' => 2, 'name' => 'Season 2'],
            ],
            'backdrop_path' => '/backdrop.jpg',
            'poster_path' => '/poster.jpg',
        ], 200);
    });

    $response = $this->get('/watch/tv/123/2/1');

    $response->assertOk();
    $response->assertSee('is-active');
});

it('has season switch buttons with wire:click', function () {
    Http::fake(function ($request) {
        $url = $request->url();

        if (str_contains($url, '/season/')) {
            return Http::response([
                'episodes' => [
                    ['episode_number' => 1, 'name' => 'Pilot', 'overview' => 'First episode.'],
                ],
            ], 200);
        }

        return Http::response([
            'name' => 'Test Show',
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1'],
                ['season_number' => 2, 'name' => 'Season 2'],
            ],
            'backdrop_path' => '/backdrop.jpg',
            'poster_path' => '/poster.jpg',
        ], 200);
    });

    $response = $this->get('/watch/tv/123/1/1');

    $response->assertOk();
    $response->assertSee('switchSeason(1)');
    $response->assertSee('switchSeason(2)');
});
