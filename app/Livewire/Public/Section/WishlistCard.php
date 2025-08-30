<?php

namespace App\Livewire\Public\Section;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WishlistCard extends Component
{
     public $wishlistItems;

    public function mount()
    {
        $this->loadWishlist();
    }

    public function removeFromWishlist($productId)
    {
        if (!Auth::check()) {
            $this->dispatch('notify', ['message' => 'Please log in to manage your wishlist', 'type' => 'error']);
            return;
        }
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();
        $this->dispatch('notify', ['message' => 'Removed from wishlist', 'type' => 'success']);
        $this->loadWishlist();
    }

    protected function loadWishlist()
    {
        if (Auth::check()) {
            $this->wishlistItems = Wishlist::where('user_id', Auth::id())
                ->with(['product' => function ($query) {
                    $query->with(['images' => function ($q) {
                        $q->where('is_primary', true);
                    }]);
                }])
                ->get();
        } else {
            $this->wishlistItems = [];
        }
    }
    public function render()
    {
        return view('livewire.public.section.wishlist-card');
    }
}
