<?php

namespace App\Livewire\Public\Section;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Products extends Component
{
    public $products;
    public $wishlist = [];

    public function mount()
    {
        $this->products = Product::with([
            'images' => function ($query) {
                $query->where('is_primary', true);
            }
        ])
            ->select('id', 'name', 'slug', 'price', 'discount_price')
            ->where('status', true)
            ->where('featured', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $this->loadWishlist();
    }

    public function toggleWishlist($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        // Check if product is in wishlist
        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
        if ($wishlistItem) {
            // Remove from wishlist
            $wishlistItem->delete();
            $this->dispatch('notify', ['message' => 'Removed from wishlist', 'type' => 'success']);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $this->dispatch('notify', ['message' => 'Added to wishlist', 'type' => 'success']);

        }

        // Reload wishlist status
        $this->loadWishlist();
    }

    protected function loadWishlist()
    {
        if (Auth::check()) {
            $this->wishlist = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        } else {
            $this->wishlist = [];
        }
    }

    public function render()
    {
        return view('livewire.public.section.products');
    }
}