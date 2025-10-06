<?php

namespace App\Livewire\Public\Page;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Privacy-Policy')]

class PrivacyPolicy extends Component
{
    public function render()
    {
        return view('livewire.public.page.privacy-policy');
    }
}
