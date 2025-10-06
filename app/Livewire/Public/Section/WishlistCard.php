<?php

namespace App\Livewire\Public\Section;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('wishlist')]
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

    public function addToCart($productId, CartService $cartService)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to add items to your cart.');
        }

        $result = $cartService->addToCart($productId, 1, null); // Default quantity=1, no variant

        if ($result['success']) {
            $this->dispatch('notify', ['message' => $result['message'], 'type' => 'success']);
            $this->dispatch('cartUpdated'); // Optional: If other components need this
        } else {
            return redirect()->to($result['redirect'])->with('error', $result['message']);
        }

        return redirect()->route('myCart')
        ->with('success', $result['message']);
        
    }
    public function render()
    {
        return view('livewire.public.section.wishlist-card');
    }
}
