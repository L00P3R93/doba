<?php

use App\Facades\TMDB;
use App\Livewire\Search;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

beforeEach(function () {
    Cache::flush();
});

it('renders search component in navbar', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSeeLivewire(Search::class);
});

it('has search button with aria-label', function () {
    Livewire::test(Search::class)
        ->assertSee('aria-label=')
        ->assertSee('Search movies and TV shows');
});

it('has search input with debounce', function () {
    Livewire::test(Search::class)
        ->call('close')
        ->set('open', true)
        ->assertSee('wire:model.live.debounce.300ms');
});

it('does not show results when query is too short', function () {
    Livewire::test(Search::class)
        ->call('close')
        ->set('open', true)
        ->set('query', 'a')
        ->assertSee('Type at least 2 characters')
        ->assertSet('results', []);
});

it('shows no results message when search returns empty', function () {
    TMDB::shouldReceive('search')->once()->with('xyznonexistent')->andReturn([]);

    Livewire::test(Search::class)
        ->call('close')
        ->set('open', true)
        ->set('query', 'xyznonexistent')
        ->assertSee('No results for')
        ->assertSet('results', []);
});

it('limits results to 10 items', function () {
    $items = collect(range(1, 15))->map(fn ($i) => [
        'tmdb_id' => $i,
        'type' => 'movie',
        'title' => "Movie {$i}",
        'image' => 'https://image.tmdb.org/t/p/w500/poster.jpg',
        'year' => '2024',
        'overview' => 'Overview',
        'vote_average' => 7.5,
    ])->toArray();

    TMDB::shouldReceive('search')->once()->with('test query')->andReturn($items);

    Livewire::test(Search::class)
        ->call('close')
        ->set('open', true)
        ->set('query', 'test query')
        ->assertSet('results', array_slice($items, 0, 10));
});

it('clears state on close', function () {
    Livewire::test(Search::class)
        ->set('open', true)
        ->set('query', 'test')
        ->call('close')
        ->assertSet('open', false)
        ->assertSet('query', '')
        ->assertSet('results', []);
});
