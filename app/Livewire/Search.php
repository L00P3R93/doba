<?php

namespace App\Livewire;

use App\Facades\TMDB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Search extends Component
{
    public string $query = '';

    public array $results = [];

    public bool $loading = false;

    public bool $open = false;

    public function updatedQuery(): void
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            $this->loading = false;

            return;
        }

        $this->loading = true;
        $this->results = array_slice(TMDB::search($this->query), 0, 10);
        $this->loading = false;
    }

    public function close(): void
    {
        $this->open = false;
        $this->query = '';
        $this->results = [];
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.search');
    }
}
