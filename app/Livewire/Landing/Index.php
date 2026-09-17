<?php

namespace App\Livewire\Landing;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kawan Bisnis - AI Growth Team untuk UMKM')]
#[Layout('layouts.guest')]
class Index extends Component
{
    public function render(): View
    {
        return view('livewire.landing.index');
    }
}
