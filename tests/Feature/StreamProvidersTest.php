<?php

use App\Services\StreamingProviderService;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('has all seven providers configured', function () {
    $providers = config('streaming.providers');

    expect($providers)->toHaveKeys([
        'vixsrc', 'vidfast', 'vidking', 'vidrock', 'vidsrc_mov', 'vidcore', 'vsembed',
    ]);
});

it('resolves movie URL for every provider', function () {
    $service = app(StreamingProviderService::class);
    $tmdbId = 550;
    $imdbId = 'tt0137523';

    foreach (config('streaming.providers') as $key => $config) {
        $url = $service->getMovieUrl($tmdbId, $imdbId, $key);

        expect($url)->toBeString()
            ->toContain($config['base_url'])
            ->toContain($imdbId);
    }
});

it('resolves TV URL for every provider', function () {
    $service = app(StreamingProviderService::class);
    $tmdbId = 1399;
    $season = 1;
    $episode = 3;

    foreach (config('streaming.providers') as $key => $config) {
        $url = $service->getTvUrl($tmdbId, $season, $episode, null, $key);

        expect($url)->toBeString()
            ->toContain($config['base_url'])
            ->toContain((string) $season)
            ->toContain((string) $episode);
    }
});

it('replaces s and e placeholders for vidsrc_mov', function () {
    $service = app(StreamingProviderService::class);

    $url = $service->getTvUrl(1399, 2, 5, null, 'vidsrc_mov');

    expect($url)->toBeString()
        ->toContain('/embed/tv/1399/2/5')
        ->not->toContain('{s}')
        ->not->toContain('{e}');
});

it('replaces season and episode placeholders for standard providers', function () {
    $service = app(StreamingProviderService::class);

    $url = $service->getTvUrl(1399, 3, 7, null, 'vixsrc');

    expect($url)->toBeString()
        ->toContain('/tv/1399/3/7')
        ->not->toContain('{season}')
        ->not->toContain('{episode}');
});

it('returns null for invalid provider name', function () {
    $service = app(StreamingProviderService::class);

    $url = $service->getMovieUrl(550, 'tt0137523', 'nonexistent');

    expect($url)->toBeNull();
});

it('movie watch page shows numbered server buttons', function () {
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
    $response->assertSee('Server 1');
    $response->assertSee('Server 2');
    $response->assertSee('Server 3');
});

it('TV watch page shows numbered server buttons', function () {
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
    $response->assertSee('S1');
    $response->assertSee('S2');
    $response->assertSee('S3');
});
