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
            'firstVariantImage' => function ($query) {
                $query->where('is_primary', true)->limit(1);
            }
        ])
            ->select('id', 'name', 'slug', 'price', 'discount_price')
            ->where('status', true)
            ->where('featured', true)
            ->inRandomOrder()
            ->latest()
            ->limit(5)
            ->get();
        $this->loadWishlist();
        // Removed incorrect dispatch: $this->dispatch("add-to-cart", productId: $product->id);
    }



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