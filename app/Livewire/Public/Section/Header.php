<?php

namespace App\Livewire\Public\Section;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Header extends Component
{
    public $cartCount = 0;
    public $wishlistCount = 0;
    public $mobileMenuOpen = false;

    public function mount()
    {
        $this->updateCounts();
        $this->mobileMenuOpen = false;
    }

    public function toggleMobileMenu()
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
        $this->dispatch('mobile-menu-toggled', mobileMenuOpen: $this->mobileMenuOpen);
    }

    public function resetMobileMenu()
    {
        $this->mobileMenuOpen = false;
        $this->dispatch('mobile-menu-toggled', mobileMenuOpen: $this->mobileMenuOpen);
    }

   protected function updateCounts()
{
    if (Auth::check()) {
        $userId = Auth::id();
        
        // Cache keys for cart and wishlist counts
        $cartCacheKey = 'cart_count_' . $userId;
        $wishlistCacheKey = 'wishlist_count_' . $userId;

        // Retrieve counts from cache or database with a 10-minute TTL
        $this->cartCount = Cache::remember($cartCacheKey, now()->addMinutes(10), function () use ($userId) {
            return Cart::where('user_id', $userId)->count();
        });

        $this->wishlistCount = Cache::remember($wishlistCacheKey, now()->addMinutes(10), function () use ($userId) {
            return Wishlist::where('user_id', $userId)->count();
        });
    } else {
        $this->cartCount = 0;
        $this->wishlistCount = 0;
    }
}

    public function render()
    {
        $this->updateCounts();
        return view('livewire.public.section.header');
    }
}