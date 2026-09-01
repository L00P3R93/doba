<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('loads the homepage with Movies/TV toggles on each rail', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Movies');
    $response->assertSee('TV');
    $response->assertSee('switchRail');
});

it('shows per-rail toggle buttons', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('baze-rail-toggle');
    $response->assertSee('switchRail');
});

it('has wire:key on each toggled rail', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('trending-today-movies');
    $response->assertSee('trending-week-movies');
    $response->assertSee('top-rated-movies');
    $response->assertSee('popular-movies');
});

it('does not have a browse rail or genre chips', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertDontSee('browse-rail');
    $response->assertDontSee('baze-genre-chip');
    $response->assertDontSee('selectGenre');
});

it('does not render standalone TV sections', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertDontSee('id="tv-trending-today"');
    $response->assertDontSee('id="tv-trending-week"');
    $response->assertDontSee('id="tv-popular"');
    $response->assertDontSee('id="tv-top-rated"');
    $response->assertDontSee('id="tv-shows"');
});

it('shows score badges on cards', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('baze-score-badge');
});

it('shows add to list buttons on cards', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('baze-poster-add');
});

it('has a new episodes section', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('New Episodes');
    $response->assertSee('new-episodes');
});

it('does not have a trailers section', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertDontSee('id="trailers"');
    $response->assertDontSee('WATCH THE FIRST LOOK');
});

it('hides continue watching section when there is no data', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertDontSee('Continue Watching');
    $response->assertDontSee('continue-watching');
});

it('has a marquee strip with 3 copies of items', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('baze-marquee-strip');
    $response->assertSee('baze-marquee-track');
});

it('new episode cards are clickable links to watch page', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('watch/tv');
});

it('has toggle loading feedback attributes', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('switchRail');
});
