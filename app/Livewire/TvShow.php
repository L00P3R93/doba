<?php

namespace App\Livewire;

use App\Facades\TMDB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('TV Show — DobaPlay')]
#[Layout('layouts.marketing', [
    'metaDescription' => 'Browse seasons and episodes on DobaPlay.',
    'metaImage' => null,
    'keywords' => 'doba, dobaplay, tv show',
    'jsonLd' => null,
])]
class TvShow extends Component
{
    public int $tmdbId;

    public array $show = [];

    public int $selectedSeason = 1;

    /** @var array<int, array{season_number: int, name: string, overview: string|null, poster_path: string|null, episode_count: int}> */
    public array $seasons = [];

    /** @var array<int, array{episode_number: int, name: string, overview: string|null, still_path: string|null, air_date: string|null, runtime: int|null}> */
    public array $episodes = [];

    public function mount(int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
        $this->show = TMDB::tvDetail($tmdbId) ?? [];

        if (empty($this->show)) {
            abort(404);
        }

        $this->seasons = collect($this->show['seasons'] ?? [])
            ->filter(fn (array $s) => ($s['season_number'] ?? 0) >= 1)
            ->values()
            ->all();

        $firstSeason = $this->seasons[0]['season_number'] ?? 1;
        $this->selectedSeason = $firstSeason;
        $this->loadSeason($firstSeason);
    }

    public function loadSeason(int $season): void
    {
        $this->selectedSeason = $season;
        $data = TMDB::tvSeason($this->tmdbId, $season);
        $this->episodes = $data['episodes'] ?? [];
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.tv-show');
    }
}
