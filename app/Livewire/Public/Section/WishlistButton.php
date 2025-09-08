<?php

namespace App\Livewire\Public\Section;

use App\Services\WishlistService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class WishlistButton extends Component
{
    public $productId;
    public $isInWishlist = false;

    protected $listeners = ['wishlistUpdated' => 'refresh'];

    public function mount(int $productId)
    {
        $this->productId = $productId;
        $this->refresh();
    }

    public function refresh()
    {
        $this->isInWishlist = app(WishlistService::class)->isInWishlist($this->productId);
    }

    public function toggleWishlist()
    {
        if (!Auth::check()) {
         return $this->redirectRoute("login",navigate:true);
        }

        $wishlistService = app(WishlistService::class);
        $result = $this->isInWishlist
            ? $wishlistService->removeFromWishlist($this->productId)
            : $wishlistService->addToWishlist($this->productId);

        if ($result['success']) {
            $this->isInWishlist = !$this->isInWishlist;
            session()->flash('wishlist_message', $result['message']);
            $this->dispatch('wishlistUpdated');
        } else {
            session()->flash('wishlist_error', $result['message']);
        }
    }

    public function render()
    {
        return view('livewire.public.section.wishlist-button');
    }
}
