<?php

namespace App\Livewire\Public\Section;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Header extends Component
{
    public $cartCount;


    public function mount()
    {
        $this->cartCount = Cart::where('user_id', Auth()->id())->count();
    }


    public function render()
    {
        return view('livewire.public.section.header');
    }
}
