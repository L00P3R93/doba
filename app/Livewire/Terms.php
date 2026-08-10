<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Terms & Conditions | Doba Play')]
#[Layout('layouts.marketing')]
class Terms extends Component
{
    public function render(): Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.⚡terms');
    }
}
