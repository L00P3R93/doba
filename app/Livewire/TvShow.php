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

    public function mount(int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
        $this->show = TMDB::tvDetail($tmdbId) ?? [];

        if (empty($this->show)) {
            abort(404);
        }
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.tv-show');
    }
}
