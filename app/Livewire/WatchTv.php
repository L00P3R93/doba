<?php

namespace App\Livewire;

use App\Facades\TMDB;
use App\Services\StreamingProviderService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Watch Episode — DobaPlay')]
#[Layout('layouts.marketing', [
    'metaDescription' => 'Stream this episode on DobaPlay.',
    'metaImage' => null,
    'keywords' => 'doba, dobaplay, stream tv show',
    'jsonLd' => null,
])]
class WatchTv extends Component
{
    public int $tmdbId;

    public int $season;

    public int $episode;

    public array $show = [];

    public array $seasons = [];

    public array $episodes = [];

    public array $episodeData = [];

    public string $embedUrl = '';

    public string $activeProvider = '';

    public bool $saved = false;

    /** @var array{name: string, key: string}[] */
    public array $providers = [];

    public int $totalEpisodes = 0;

    public function mount(int $tmdbId, int $season, int $episode): void
    {
        $this->tmdbId = $tmdbId;
        $this->season = $season;
        $this->episode = $episode;
        $this->show = TMDB::tvDetail($tmdbId) ?? [];

        if (empty($this->show)) {
            abort(404);
        }

        $this->seasons = collect($this->show['seasons'] ?? [])
            ->where('season_number', '>', 0)
            ->values()
            ->all();

        $this->loadProviders();
        $this->loadSeasonData();
        $this->loadPlayer();
    }

    public function goToEpisode(int $season, int $episode): void
    {
        $this->season = $season;
        $this->episode = $episode;
        $this->loadSeasonData();
        $this->loadPlayer();
    }

    public function switchSeason(int $season): void
    {
        $this->season = $season;
        $this->episode = 1;
        $this->loadSeasonData();
        $this->loadPlayer();
    }

    public function prevEpisode(): void
    {
        if ($this->episode > 1) {
            $this->goToEpisode($this->season, $this->episode - 1);
        }
    }

    public function nextEpisode(): void
    {
        if ($this->episode < $this->totalEpisodes) {
            $this->goToEpisode($this->season, $this->episode + 1);
        }
    }

    public function switchProvider(string $provider): void
    {
        $this->activeProvider = $provider;

        $streaming = app(StreamingProviderService::class);
        $this->embedUrl = $streaming->getTvUrl(
            $this->tmdbId,
            $this->season,
            $this->episode,
            null,
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

    protected function loadSeasonData(): void
    {
        $data = TMDB::tvSeason($this->tmdbId, $this->season);
        $episodes = $data['episodes'] ?? [];

        $this->episodes = $episodes;
        $this->totalEpisodes = count($episodes);

        $this->episodeData = collect($episodes)
            ->firstWhere('episode_number', $this->episode) ?? [];
    }

    protected function loadPlayer(): void
    {
        $provider = app(StreamingProviderService::class);
        $this->activeProvider = $provider->getPrimaryProvider();
        $this->embedUrl = $provider->getTvUrl(
            $this->tmdbId,
            $this->season,
            $this->episode
        ) ?? '';
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.watch-tv');
    }
}
