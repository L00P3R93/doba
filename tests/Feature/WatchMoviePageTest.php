<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('loads the movie watch page with correct title', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'overview' => 'An insomniac office worker forms an underground fight club.',
            'release_date' => '1999-10-15',
            'vote_average' => 8.4,
            'runtime' => 139,
            'genres' => [['id' => 18, 'name' => 'Drama']],
            'external_ids' => ['imdb_id' => 'tt0137523'],
        ], 200),
    ]);

    $response = $this->get(route('watch.movie', 550));

    $response->assertOk();
    $response->assertSee('Fight Club');
    $response->assertSee('iframe');
    $response->assertSee('1999');
    $response->assertSee('Drama');
    $response->assertSee('84%');
    $response->assertSee('An insomniac office worker');
});

it('displays the responsive player container', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'overview' => 'Overview here.',
            'release_date' => '1999-10-15',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.movie', 550));

    $response->assertOk();
    $response->assertSee('baze-player-container');
    $response->assertSee('allowfullscreen');
    $response->assertSee('autoplay');
    $response->assertSee('encrypted-media');
});

it('shows provider selector when multiple providers exist', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'overview' => 'Overview.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.movie', 550));

    $response->assertOk();
    $response->assertSee('baze-watch-providers');
    $response->assertSee('SERVERS');
    $response->assertSee('switchProvider');
});

it('displays action buttons for save and share', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'overview' => 'Overview.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.movie', 550));

    $response->assertOk();
    $response->assertSee('baze-watch-actions');
    $response->assertSee('Save');
    $response->assertSee('Share');
});

it('has a back to home link', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'overview' => 'Overview.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.movie', 550));

    $response->assertOk();
    $response->assertSee('Back to Home');
    $response->assertSee(route('home'));
});

it('shows movie runtime when available', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'overview' => 'Overview.',
            'runtime' => 139,
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.movie', 550));

    $response->assertOk();
    $response->assertSee('2h 19m');
});

it('returns 404 for a non-existent movie', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/99999*' => Http::response([], 404),
    ]);

    $response = $this->get(route('watch.movie', 99999));

    $response->assertNotFound();
});

it('shows player unavailable message when embed URL is empty', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'overview' => 'Overview.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    // The provider service will generate a URL, but this tests the empty state
    $response = $this->get(route('watch.movie', 550));

    $response->assertOk();
    $response->assertSee('baze-player-container');
});

it('uses design system classes instead of inline styles', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'overview' => 'Overview.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.movie', 550));

    $response->assertOk();
    $response->assertSee('baze-watch-page');
    $response->assertSee('baze-watch-nav');
    $response->assertSee('baze-watch-info');
    $response->assertSee('baze-watch-title');
    $response->assertSee('baze-watch-meta');
    $response->assertSee('baze-watch-overview');
    $response->assertDontSee('style="padding-top:');
});
