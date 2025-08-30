<?php

namespace App\Livewire\Public\Section;

use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class Products extends Component
{
    public $Products;

   public function mount()
{
    $this->Products = Product::with(['images' => function($query) {
            $query->where('is_primary', true); 
        }])
        ->select('id', 'name', 'slug', 'price', 'discount_price')
        ->where('status', true)
        ->where('featured', true)
        ->inRandomOrder()
        ->latest()
        ->limit(4)
        ->get();

        //dd($testing);

}

 public function toggleWishlist($productId)
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        // Check if product is in wishlist
        $wishlistItem = Wishlist::where('product_id', $productId)
            ->where(function ($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                      ->orWhere('session_id', $sessionId);
            })
            ->first();

        if ($wishlistItem) {
            // Remove from wishlist
            $wishlistItem->delete();
            $this->dispatch('notify', ['message' => 'Removed from wishlist', 'type' => 'success']);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
            ]);
            $this->dispatch('notify', ['message' => 'Added to wishlist', 'type' => 'success']);
        }

        // Reload wishlist status
        $this->loadWishlist();
    }

    protected function loadWishlist()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        $this->wishlist = Wishlist::where('user_id', $userId)
            ->orWhere('session_id', $sessionId)
            ->pluck('product_id')
            ->toArray();
    }


    public function render()
    {
        return view('livewire.public.section.products');
    }
}
