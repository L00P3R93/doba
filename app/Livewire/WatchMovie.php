<?php

namespace App\Livewire;

use App\Facades\TMDB;
use App\Services\StreamingProviderService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Watch Movie — DobaPlay')]
#[Layout('layouts.marketing', [
    'metaDescription' => 'Stream this movie on DobaPlay.',
    'metaImage' => null,
    'keywords' => 'doba, dobaplay, stream movie',
    'jsonLd' => null,
])]
class WatchMovie extends Component
{
    public int $tmdbId;

    public array $movie = [];

    public string $embedUrl = '';

    public string $activeProvider = '';

    public bool $saved = false;

    /** @var array{name: string, key: string}[] */
    public array $providers = [];

    public function mount(int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
        $this->movie = TMDB::movieDetail($tmdbId) ?? [];

        if (empty($this->movie)) {
            abort(404);
        }

        $this->loadProviders();
        $this->loadPlayer();
    }

    public function switchProvider(string $provider): void
    {
        $this->activeProvider = $provider;

        $streaming = app(StreamingProviderService::class);
        $this->embedUrl = $streaming->getMovieUrl(
            $this->tmdbId,
            $this->movie['imdb_id'] ?? null,
            $provider
        ) ?? '';
    }

    protected function loadProviders(): void
    {
        $streaming = app(StreamingProviderService::class);
        $all = $streaming->getProviders();

        $this->providers = collect($all)
            ->map(fn (array $p, string $key) => [
                'name' => $p['name'],
                'key' => $key,
            ])
            ->values()
            ->all();
    }

    protected function loadPlayer(): void
    {
        $provider = app(StreamingProviderService::class);
        $this->activeProvider = $provider->getPrimaryProvider();
        $this->embedUrl = $provider->getMovieUrl(
            $this->tmdbId,
            $this->movie['imdb_id'] ?? null
        ) ?? '';
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.watch-movie');
    }
}
