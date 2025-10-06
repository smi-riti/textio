<?php

namespace App\Livewire\Public\Page;

use Livewire\Attributes\Title;
use Livewire\Component;
    #[Title('About')]

class AboutPage extends Component
{
    public function render()
    {
        return view('livewire.public.page.about-page');
    }
}
