<?php

namespace App\Livewire\Public\Section;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Products extends Component
{
    public $Products;
    public $wishlist = [];

    public function mount()
    {
        $this->Products = Product::with([
            'images' => function ($query) {
                $query->where('is_primary', true);
            }
        ])
            ->select('id', 'name', 'slug', 'price', 'discount_price')
            ->where('status', true)
            ->where('featured', true)
            ->inRandomOrder()
            ->latest()
            ->limit(4)
            ->get();
        $this->loadWishlist();
        // Removed incorrect dispatch: $this->dispatch("add-to-cart", productId: $product->id);
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

    // public function toggleWishlist($productId)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('login')->with('error', 'Please log in to manage your wishlist.');
    //     }

    //     $userId = Auth::id();

    //     // Check if product is in wishlist
    //     $wishlistItem = Wishlist::where('product_id', $productId)
    //         ->where('user_id', $userId)
    //         ->first();

    //     if ($wishlistItem) {
    //         // Remove from wishlist
    //         $wishlistItem->delete();
    //         $this->dispatch('notify', ['message' => 'Removed from wishlist', 'type' => 'success']);
    //     } else {
    //         // Add to wishlist
    //         Wishlist::create([
    //             'user_id' => $userId,
    //             'product_id' => $productId,
    //         ]);
    //         $this->dispatch('notify', ['message' => 'Added to wishlist', 'type' => 'success']);
    //     }

    //     // Reload wishlist status
    //     $this->loadWishlist();
    // }

    protected function loadWishlist()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $this->wishlist = Wishlist::where('user_id', $userId)
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