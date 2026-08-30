<?php

use App\Services\StreamingProviderService;
use Tests\TestCase;

uses(TestCase::class);

it('returns the primary provider name', function () {
    $service = new StreamingProviderService;

    expect($service->getPrimaryProvider())->toBe('vixsrc');
});

it('returns all configured providers', function () {
    $service = new StreamingProviderService;
    $providers = $service->getProviders();

    expect($providers)
        ->toBeArray()
        ->toHaveKeys(['vixsrc', 'vidfast', 'vidking'])
        ->and($providers['vixsrc']['name'])->toBe('VixSRC')
        ->and($providers['vidfast']['name'])->toBe('VidFast')
        ->and($providers['vidking']['name'])->toBe('VidKing');
});

it('returns a specific provider config', function () {
    $service = new StreamingProviderService;
    $provider = $service->getProvider('vixsrc');

    expect($provider)
        ->toBeArray()
        ->toHaveKeys(['name', 'base_url', 'movie_path', 'tv_path', 'params'])
        ->and($provider['base_url'])->toBe('https://vixsrc.to');
});

it('returns null for unknown provider', function () {
    $service = new StreamingProviderService;

    expect($service->getProvider('nonexistent'))->toBeNull();
});

it('generates a movie URL with TMDB ID using primary provider', function () {
    $service = new StreamingProviderService;
    $url = $service->getMovieUrl(550);

    expect($url)->toBe('https://vixsrc.to/movie/550?primaryColor=ff6b1a&autoplay=false');
});

it('generates a movie URL with IMDb ID', function () {
    $service = new StreamingProviderService;
    $url = $service->getMovieUrl(550, 'tt0137523');

    expect($url)->toBe('https://vixsrc.to/movie/tt0137523?primaryColor=ff6b1a&autoplay=false');
});

it('generates a TV URL with TMDB ID', function () {
    $service = new StreamingProviderService;
    $url = $service->getTvUrl(1399, 1, 1);

    expect($url)->toBe('https://vixsrc.to/tv/1399/1/1?primaryColor=ff6b1a&autoplay=false');
});

it('generates a TV URL with IMDb ID', function () {
    $service = new StreamingProviderService;
    $url = $service->getTvUrl(1399, 1, 1, 'tt0945280');

    expect($url)->toBe('https://vixsrc.to/tv/tt0945280/1/1?primaryColor=ff6b1a&autoplay=false');
});

it('generates movie URL with a specific provider override', function () {
    $service = new StreamingProviderService;
    $url = $service->getMovieUrl(550, null, 'vidfast');

    expect($url)->toBe('https://vidfast.pro/movie/550?theme=ff6b1a&autoPlay=false');
});

it('generates TV URL with vidking provider', function () {
    $service = new StreamingProviderService;
    $url = $service->getTvUrl(1399, 3, 5, null, 'vidking');

    expect($url)->toBe('https://www.vidking.net/embed/tv/1399/3/5?color=ff6b1a&autoPlay=false');
});

it('returns null for unknown provider override', function () {
    $service = new StreamingProviderService;
    $url = $service->getMovieUrl(550, null, 'nonexistent');

    expect($url)->toBeNull();
});

it('generates correct vidfast TV URL with season and episode', function () {
    $service = new StreamingProviderService;
    $url = $service->getTvUrl(63174, 1, 5, null, 'vidfast');

    expect($url)->toBe('https://vidfast.pro/tv/63174/1/5?theme=ff6b1a&autoPlay=false');
});
