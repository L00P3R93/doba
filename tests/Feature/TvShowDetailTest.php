<?php

use Illuminate\Support\Facades\Http;

it('loads the TV show detail page with show title', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'air_date' => '2011-04-17', 'runtime' => 62, 'overview' => 'The HBO series premiere.', 'still_path' => '/still.jpg'],
                ['episode_number' => 2, 'name' => 'The Kingsroad', 'air_date' => '2011-04-24', 'runtime' => 56, 'overview' => 'The king travels.', 'still_path' => '/still2.jpg'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/1399*' => Http::response([
            'id' => 1399,
            'name' => 'Game of Thrones',
            'overview' => 'Epic fantasy drama.',
            'first_air_date' => '2011-04-17',
            'last_air_date' => '2019-05-19',
            'status' => 'Ended',
            'vote_average' => 8.4,
            'number_of_seasons' => 8,
            'genres' => [['id' => 18, 'name' => 'Drama'], ['id' => 10765, 'name' => 'Sci-Fi & Fantasy']],
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1', 'episode_count' => 10],
                ['season_number' => 2, 'name' => 'Season 2', 'episode_count' => 10],
            ],
            'backdrop_path' => '/backdrop.jpg',
        ], 200),
    ]);

    $response = $this->get(route('tv.show', 1399));

    $response->assertOk();
    $response->assertSee('Game of Thrones');
    $response->assertSee('Drama');
    $response->assertSee('Back to Home');
});

it('displays season selector buttons', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'air_date' => '2011-04-17', 'overview' => 'Premiere.'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/1399*' => Http::response([
            'id' => 1399,
            'name' => 'Game of Thrones',
            'overview' => 'Epic fantasy.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
            'number_of_seasons' => 2,
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1', 'episode_count' => 10],
                ['season_number' => 2, 'name' => 'Season 2', 'episode_count' => 10],
            ],
        ], 200),
    ]);

    $response = $this->get(route('tv.show', 1399));

    $response->assertOk();
    $response->assertSee('SEASONS');
    $response->assertSee('S1');
    $response->assertSee('S2');
    $response->assertSee('loadSeason');
});

it('displays episode list for selected season', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'air_date' => '2011-04-17', 'runtime' => 62, 'overview' => 'The premiere.', 'still_path' => '/still.jpg'],
                ['episode_number' => 2, 'name' => 'The Kingsroad', 'air_date' => '2011-04-24', 'runtime' => 56, 'overview' => 'Journey south.', 'still_path' => '/still2.jpg'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/1399*' => Http::response([
            'id' => 1399,
            'name' => 'Game of Thrones',
            'overview' => 'Epic fantasy.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1', 'episode_count' => 2],
            ],
        ], 200),
    ]);

    $response = $this->get(route('tv.show', 1399));

    $response->assertOk();
    $response->assertSee('Winter Is Coming');
    $response->assertSee('The Kingsroad');
    $response->assertSee('62m');
    $response->assertSee('56m');
    $response->assertSee('watch/tv/1399/1/1');
    $response->assertSee('watch/tv/1399/1/2');
});

it('episode cards link to watch pages', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'air_date' => '2011-04-17', 'overview' => 'Premiere.'],
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
    $response->assertSee(route('watch.tv', [1399, 1, 1]));
});

it('returns 404 for a non-existent TV show', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/99999*' => Http::response([], 404),
    ]);

    $response = $this->get(route('tv.show', 99999));

    $response->assertNotFound();
});

it('uses design system classes', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'air_date' => '2011-04-17', 'overview' => 'Premiere.'],
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
    $response->assertSee('baze-tv-page');
    $response->assertSee('baze-tv-hero');
    $response->assertSee('baze-tv-seasons');
    $response->assertSee('baze-tv-episodes');
    $response->assertSee('baze-tv-episode-card');
    $response->assertDontSee('style="padding-top:');
});
