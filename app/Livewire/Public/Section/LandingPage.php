<?php

namespace App\Livewire\Public\Section;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home')]

class LandingPage extends Component
{
    public $moreProducts;

    public function mount()
    {
        $this->moreProducts = Product::with(['images' => function ($query) {
                $query->where('is_primary', true);
            }])
            ->where('status', true)
            ->where('is_customizable', true)
            ->inRandomOrder()
            ->limit(4)
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.public.section.landing-page');
    }
}
