<?php

namespace App\Http\Livewire\User\Product;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class AddItem extends Component
{
    public $product;
    public $quantity = 1;
    public $selectedColor;
    public $selectedStorage;

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

    }

    public function handleCartItem()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Find the product variant ID based on selected color and storage
        $productVariantId = null;
        if ($this->selectedColor || $this->selectedStorage) {
            $variantQuery = $this->product->variants();
            if ($this->selectedColor) {
                $variantQuery->where('type', 'color')->where('value', $this->selectedColor);
            }
            if ($this->selectedStorage) {
                $variantQuery->where('type', 'storage')->where('value', $this->selectedStorage);
            }
            $variant = $variantQuery->first();
            $productVariantId = $variant ? $variant->id : null;
        }

        // Save to Cart model
        Cart::create([
            'product_id' => $this->product->id,
            'user_id' => Auth::id(),
            'product_variant_id' => $productVariantId,
            'quantity' => $this->quantity,
        ]);

        session()->flash('message', 'Product added to cart successfully!');
        return redirect()->route('public.cart');
    }

    public function increment()
    {
        $this->quantity++;
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function selectColor($color)
    {
        $this->selectedColor = $color;
    }

    public function selectStorage($storage)
    {
        $this->selectedStorage = $storage;
    }

    public function render()
    {
        return view('livewire.user.product.add-item');
    }
}