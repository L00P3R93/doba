<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('loads the movie watch page with embed iframe', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/12345*' => Http::response([
            'id' => 12345,
            'title' => 'Test Movie',
            'overview' => 'A test movie overview.',
            'release_date' => '2024-01-15',
            'vote_average' => 8.5,
            'genres' => [['id' => 28, 'name' => 'Action']],
            'external_ids' => ['imdb_id' => 'tt1234567'],
        ], 200),
    ]);

    $response = $this->get(route('watch.movie', 12345));

    $response->assertOk();
    $response->assertSee('Test Movie');
    $response->assertSee('iframe');
    $response->assertSee('Back to Home');
});

it('returns 404 for a non-existent movie', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/99999*' => Http::response([], 404),
    ]);

    $response = $this->get(route('watch.movie', 99999));

    $response->assertNotFound();
});

it('loads the TV watch page with embed iframe', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/67890/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 3, 'name' => 'Episode Three', 'air_date' => '2024-01-15', 'overview' => 'Third episode.'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/67890*' => Http::response([
            'id' => 67890,
            'name' => 'Test TV Show',
            'overview' => 'A great TV show.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.tv', [67890, 1, 3]));

    $response->assertOk();
    $response->assertSee('Test TV Show');
    $response->assertSee('S01');
    $response->assertSee('E03');
    $response->assertSee('iframe');
});

it('returns 404 for a non-existent TV show on watch page', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/99999*' => Http::response([], 404),
    ]);

    $response = $this->get(route('watch.tv', [99999, 1, 1]));

    $response->assertNotFound();
});

it('loads the TV show detail page', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/67890/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Pilot', 'air_date' => '2024-01-01', 'overview' => 'First episode.'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/67890*' => Http::response([
            'id' => 67890,
            'name' => 'Test TV Show',
            'overview' => 'A great TV show with many seasons.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1', 'episode_count' => 1],
                ['season_number' => 2, 'name' => 'Season 2', 'episode_count' => 1],
            ],
        ], 200),
    ]);

    $response = $this->get(route('tv.show', 67890));

    $response->assertOk();
    $response->assertSee('Test TV Show');
});

it('returns 404 for a non-existent TV show detail page', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/99999*' => Http::response([], 404),
    ]);

    $response = $this->get(route('tv.show', 99999));

    $response->assertNotFound();
});

it('homepage movie cards link to watch/movie/{tmdbId}', function () {
    Http::fake([
        'api.themoviedb.org/3/*' => Http::response([
            'results' => [],
            'genres' => [],
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('watch/movie/');
});

it('tv show detail route is accessible', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'overview' => 'Premiere.'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/1399*' => Http::response([
            'id' => 1399,
            'name' => 'Game of Thrones',
            'overview' => 'Epic fantasy.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1', 'episode_count' => 1],
            ],
        ], 200),
    ]);

    $response = $this->get(route('tv.show', 1399));

    $response->assertOk();
    $response->assertSee('Game of Thrones');
});
