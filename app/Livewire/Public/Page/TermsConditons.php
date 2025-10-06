<?php

namespace App\Livewire\Public\Page;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Terms & Conditions')]
class TermsConditons extends Component
{
    public function render()
    {
        return view('livewire.public.page.terms-conditons');
    }
}
