<?php

use Illuminate\Support\Facades\Http;

it('loads the TV watch page with episode info', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'air_date' => '2011-04-17', 'runtime' => 62, 'overview' => 'The premiere.'],
                ['episode_number' => 2, 'name' => 'The Kingsroad', 'air_date' => '2011-04-24', 'runtime' => 56, 'overview' => 'Journey south.'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/1399*' => Http::response([
            'id' => 1399,
            'name' => 'Game of Thrones',
            'overview' => 'Epic fantasy.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.tv', [1399, 1, 1]));

    $response->assertOk();
    $response->assertSee('Winter Is Coming');
    $response->assertSee('S01');
    $response->assertSee('E01');
    $response->assertSee('Game of Thrones');
    $response->assertSee('Apr 17, 2011');
    $response->assertSee('62m');
});

it('displays the player iframe', function () {
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
        ], 200),
    ]);

    $response = $this->get(route('watch.tv', [1399, 1, 1]));

    $response->assertOk();
    $response->assertSee('baze-player-container');
    $response->assertSee('iframe');
    $response->assertSee('allowfullscreen');
    $response->assertSee('autoplay');
});

it('shows provider selector', function () {
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
        ], 200),
    ]);

    $response = $this->get(route('watch.tv', [1399, 1, 1]));

    $response->assertOk();
    $response->assertSee('baze-watch-providers');
    $response->assertSee('SERVERS');
    $response->assertSee('switchProvider');
});

it('shows episode navigation with prev/next', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'overview' => 'Premiere.'],
                ['episode_number' => 2, 'name' => 'The Kingsroad', 'overview' => 'Journey.'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/1399*' => Http::response([
            'id' => 1399,
            'name' => 'Game of Thrones',
            'overview' => 'Epic fantasy.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.tv', [1399, 1, 1]));

    $response->assertOk();
    $response->assertSee('prevEpisode');
    $response->assertSee('nextEpisode');
    $response->assertSee('Previous');
    $response->assertSee('Next');
    $response->assertSee('E1 of 2');
});

it('disables prev button on first episode', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/*' => Http::response([
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming', 'overview' => 'Premiere.'],
                ['episode_number' => 2, 'name' => 'The Kingsroad', 'overview' => 'Journey.'],
            ],
        ], 200),
        'api.themoviedb.org/3/tv/1399*' => Http::response([
            'id' => 1399,
            'name' => 'Game of Thrones',
            'overview' => 'Epic fantasy.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $response = $this->get(route('watch.tv', [1399, 1, 1]));

    $response->assertOk();
    $response->assertSee('disabled');
});

it('back link goes to tv show detail', function () {
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
        ], 200),
    ]);

    $response = $this->get(route('watch.tv', [1399, 1, 1]));

    $response->assertOk();
    $response->assertSee(route('tv.show', 1399));
    $response->assertSee('Back to Game of Thrones');
});

it('returns 404 for non-existent TV show', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/99999*' => Http::response([], 404),
    ]);

    $response = $this->get(route('watch.tv', [99999, 1, 1]));

    $response->assertNotFound();
});

it('uses design system classes', function () {
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
        ], 200),
    ]);

    $response = $this->get(route('watch.tv', [1399, 1, 1]));

    $response->assertOk();
    $response->assertSee('baze-watch-page');
    $response->assertSee('baze-tv-episode-label');
    $response->assertSee('baze-tv-nav');
    $response->assertSee('baze-watch-actions');
    $response->assertDontSee('style="padding-top:');
});
