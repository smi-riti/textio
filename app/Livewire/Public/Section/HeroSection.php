<?php

namespace App\Livewire\Public\Section;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home')]
class HeroSection extends Component
{
    public function render()
    {
        return view('livewire.public.section.hero-section');
    }
}
