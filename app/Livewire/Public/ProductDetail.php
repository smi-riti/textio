<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductDetail extends Component
{
    public $product;
    public $size_variant_id;
    public $color_variant_id;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->product = Product::with(['images', 'category', 'brand', 'variants'])->where('slug', $slug)->firstOrFail();
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $product = $this->product;
        $size_variant_id = $this->size_variant_id ?? null;
        $color_variant_id = $this->color_variant_id ?? null;
        $quantity = $this->quantity ?? 1;

        // Check stock
        if ($product->stock < $quantity) {
            session()->flash('error', 'Not enough stock available.');
            return;
        }

        // Find or create order
        $order = \App\Models\Order::firstOrCreate(
            ['user_id' => Auth::id(), 'isOrdered' => false],
            ['order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8))]
        );

        // Find or create order item
        $orderItem = \App\Models\OrderItem::where([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'size_variant_id' => $size_variant_id,
            'color_variant_id' => $color_variant_id,
            'user_id' => Auth::id(),
        ])->first();

        if ($orderItem) {
            $orderItem->quantity += $quantity;
            $orderItem->save();
        } else {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'size_variant_id' => $size_variant_id,
                'color_variant_id' => $color_variant_id,
                'quantity' => $quantity,
                'user_id' => Auth::id(),
            ]);
        }

        // Redirect to checkout page (or cart page)
        return redirect()->route('public.cart', ['slug' => $product->slug]);
    }

    public function buyNow()
    {
        $this->addToCart();
        return redirect()->route('public.cart', ['slug' => $this->product->slug]);
    }

    public function render()
    {
        return view('livewire.public.product-detail');
    }
}
