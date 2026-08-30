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

    public array $episodeData = [];

    public string $embedUrl = '';

    public string $activeProvider = '';

    public function mount(int $tmdbId, int $season, int $episode): void
    {
        $this->tmdbId = $tmdbId;
        $this->season = $season;
        $this->episode = $episode;
        $this->show = TMDB::tvDetail($tmdbId) ?? [];

        if (empty($this->show)) {
            abort(404);
        }

        $this->loadPlayer();
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
