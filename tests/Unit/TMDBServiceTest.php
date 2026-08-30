<?php

use App\Services\TMDBService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Cache::flush();
});

it('returns the genre map with movie and TV IDs', function () {
    $service = new TMDBService;
    $map = $service->getGenreMap();

    expect($map)
        ->toBeArray()
        ->toHaveKeys(['Action', 'Drama', 'Thriller', 'Sci-Fi', 'Horror', 'War', 'Comedy', 'Crime', 'Animation', 'Family'])
        ->and($map['Action']['movie'])->toBe(28)
        ->and($map['Action']['tv'])->toBe(10759)
        ->and($map['Drama']['movie'])->toBe(18)
        ->and($map['Drama']['tv'])->toBe(18)
        ->and($map['Sci-Fi']['movie'])->toBe(878)
        ->and($map['Sci-Fi']['tv'])->toBe(10765);
});

it('returns correct genre ID for movie', function () {
    $service = new TMDBService;

    expect($service->getGenreId('Action', 'movie'))->toBe(28);
    expect($service->getGenreId('Horror', 'movie'))->toBe(27);
});

it('returns correct genre ID for TV', function () {
    $service = new TMDBService;

    expect($service->getGenreId('Action', 'tv'))->toBe(10759);
    expect($service->getGenreId('Sci-Fi', 'tv'))->toBe(10765);
});

it('returns null for unmapped genre label', function () {
    $service = new TMDBService;

    expect($service->getGenreId('Nonexistent', 'movie'))->toBeNull();
});

it('returns null for unmapped content type', function () {
    $service = new TMDBService;

    expect($service->getGenreId('Horror', 'tv'))->toBeNull();
});

it('fetches TV trending today from TMDB', function () {
    Http::fake([
        'api.themoviedb.org/3/trending/tv/day*' => Http::response([
            'results' => [
                ['id' => 1399, 'name' => 'Game of Thrones', 'backdrop_path' => '/abc.jpg', 'first_air_date' => '2011-04-17', 'genre_ids' => [18, 10765], 'vote_average' => 8.4],
                ['id' => 94997, 'name' => 'House of the Dragon', 'backdrop_path' => '/def.jpg', 'first_air_date' => '2022-08-21', 'genre_ids' => [18, 10765], 'vote_average' => 8.3],
            ],
        ], 200),
        'api.themoviedb.org/3/genre/tv/list*' => Http::response([
            'genres' => [
                ['id' => 18, 'name' => 'Drama'],
                ['id' => 10759, 'name' => 'Action & Adventure'],
                ['id' => 10765, 'name' => 'Sci-Fi & Fantasy'],
            ],
        ], 200),
    ]);

    $service = new TMDBService;
    $results = $service->tvTrendingToday(2);

    expect($results)->toHaveCount(2)
        ->and($results[0]['title'])->toBe('Game of Thrones')
        ->and($results[0]['tmdb_id'])->toBe(1399)
        ->and($results[0]['type'])->toBe('tv')
        ->and($results[0]['genre'])->toContain('Drama')
        ->and($results[1]['title'])->toBe('House of the Dragon');
});

it('fetches TV popular from TMDB', function () {
    Http::fake([
        'api.themoviedb.org/3/discover/tv*' => Http::response([
            'results' => [
                ['id' => 1399, 'name' => 'Game of Thrones', 'backdrop_path' => '/abc.jpg', 'first_air_date' => '2011-04-17', 'genre_ids' => [18], 'vote_average' => 8.4],
            ],
        ], 200),
        'api.themoviedb.org/3/genre/tv/list*' => Http::response([
            'genres' => [['id' => 18, 'name' => 'Drama']],
        ], 200),
    ]);

    $service = new TMDBService;
    $results = $service->tvPopular(1);

    expect($results)->toHaveCount(1)
        ->and($results[0]['title'])->toBe('Game of Thrones')
        ->and($results[0]['type'])->toBe('tv');
});

it('fetches TV show details from TMDB', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399*' => Http::response([
            'id' => 1399,
            'name' => 'Game of Thrones',
            'overview' => 'Seven noble families fight for control.',
            'number_of_seasons' => 8,
            'number_of_episodes' => 73,
            'external_ids' => ['imdb_id' => 'tt0945280'],
            'seasons' => [
                ['season_number' => 1, 'name' => 'Season 1', 'episode_count' => 10],
            ],
        ], 200),
    ]);

    $service = new TMDBService;
    $detail = $service->tvDetail(1399);

    expect($detail)->not->toBeNull()
        ->and($detail['id'])->toBe(1399)
        ->and($detail['name'])->toBe('Game of Thrones')
        ->and($detail['number_of_seasons'])->toBe(8)
        ->and($detail['external_ids']['imdb_id'])->toBe('tt0945280');
});

it('returns null for TV detail when TMDB returns empty', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/99999*' => Http::response([], 404),
    ]);

    $service = new TMDBService;
    $detail = $service->tvDetail(99999);

    expect($detail)->toBeNull();
});

it('fetches TV season details from TMDB', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/season/1*' => Http::response([
            'id' => 3627,
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1, 'name' => 'Winter Is Coming'],
                ['episode_number' => 2, 'name' => 'The Kingsroad'],
            ],
        ], 200),
    ]);

    $service = new TMDBService;
    $season = $service->tvSeason(1399, 1);

    expect($season)->not->toBeNull()
        ->and($season['episodes'])->toHaveCount(2)
        ->and($season['episodes'][0]['name'])->toBe('Winter Is Coming');
});

it('fetches movie details from TMDB', function () {
    Http::fake([
        'api.themoviedb.org/3/movie/550*' => Http::response([
            'id' => 550,
            'title' => 'Fight Club',
            'imdb_id' => 'tt0137523',
            'overview' => 'An insomniac office worker and a devil-may-care soap maker.',
            'genres' => [['id' => 18, 'name' => 'Drama']],
            'external_ids' => ['imdb_id' => 'tt0137523'],
        ], 200),
    ]);

    $service = new TMDBService;
    $detail = $service->movieDetail(550);

    expect($detail)->not->toBeNull()
        ->and($detail['id'])->toBe(550)
        ->and($detail['title'])->toBe('Fight Club')
        ->and($detail['imdb_id'])->toBe('tt0137523');
});

it('searches TMDB for movies and TV shows', function () {
    Http::fake([
        'api.themoviedb.org/3/search/multi*' => Http::response([
            'results' => [
                ['id' => 550, 'media_type' => 'movie', 'title' => 'Fight Club', 'backdrop_path' => '/abc.jpg', 'release_date' => '1999-10-15', 'overview' => 'An insomniac.', 'vote_average' => 8.4],
                ['id' => 1399, 'media_type' => 'tv', 'name' => 'Game of Thrones', 'backdrop_path' => '/def.jpg', 'first_air_date' => '2011-04-17', 'overview' => 'Seven noble families.', 'vote_average' => 8.4],
            ],
        ], 200),
    ]);

    $service = new TMDBService;
    $results = $service->search('fight');

    expect($results)->toHaveCount(2)
        ->and($results[0]['type'])->toBe('movie')
        ->and($results[0]['title'])->toBe('Fight Club')
        ->and($results[1]['type'])->toBe('tv')
        ->and($results[1]['title'])->toBe('Game of Thrones');
});

it('fetches external IDs for a TV show', function () {
    Http::fake([
        'api.themoviedb.org/3/tv/1399/external_ids*' => Http::response([
            'id' => 1399,
            'imdb_id' => 'tt0945280',
        ], 200),
    ]);

    $service = new TMDBService;
    $ids = $service->getExternalIds('tv', 1399);

    expect($ids)->not->toBeNull()
        ->and($ids['tmdb_id'])->toBe(1399)
        ->and($ids['imdb_id'])->toBe('tt0945280');
});

it('returns empty array when API key is not set', function () {
    config(['services.tmdb.key' => null]);

    Http::fake();

    $service = new TMDBService;
    $results = $service->tvTrendingToday();

    expect($results)->toBeArray()->toBeEmpty();

    Http::assertNothingSent();
});
