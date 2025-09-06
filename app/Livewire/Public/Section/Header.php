<?php

namespace App\Livewire\Public\Section;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Header extends Component
{
    public $cartCount;
    public $mobileMenuOpen = false;

    public function mount()
    {
        $this->cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;
        $this->mobileMenuOpen = false; // Explicitly reset on mount
    }

    public function toggleMobileMenu()
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
        $this->dispatch('mobile-menu-toggled', ['mobileMenuOpen' => $this->mobileMenuOpen]);
    }

    public function resetMobileMenu()
    {
        $this->mobileMenuOpen = false;
        $this->dispatch('mobile-menu-toggled', ['mobileMenuOpen' => $this->mobileMenuOpen]);
    }

    public function render()
    {
        return view('livewire.public.section.header');
    }
}