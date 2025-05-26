<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\OrderItem;
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
        $this->product = Product::with(['images', 'category', 'brand', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function addToCart()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            session()->flash('error', 'Please log in to add items to your cart.');
            return $this->redirectRoute('login');
        }

        // Validate inputs
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'size_variant_id' => 'nullable|exists:product_variants,id',
            'color_variant_id' => 'nullable|exists:product_variants,id',
        ]);

        // Check if an open order exists for the user
        $exist_order = Order::where([
            ['user_id', Auth::id()],
            ['isOrdered', false],
        ])->first();

        if ($exist_order) {
            // Check if the item already exists in the order
            $exist_order_item = OrderItem::where([
                ['user_id', Auth::id()],
                ['order_id', $exist_order->id],
                ['product_id', $this->product->id],
                ['size_variant_id', $this->size_variant_id],
                ['color_variant_id', $this->color_variant_id],
            ])->first();

            if ($exist_order_item) {
                // Update quantity if item exists
                $exist_order_item->quantity += $this->quantity;
                $exist_order_item->save();
            } else {
                // Create new order item
                $order_item = new OrderItem();
                $order_item->user_id = Auth::id();
                $order_item->order_id = $exist_order->id;
                $order_item->product_id = $this->product->id;
                $order_item->size_variant_id = $this->size_variant_id;
                $order_item->color_variant_id = $this->color_variant_id;
                $order_item->quantity = $this->quantity;
                $order_item->save();
            }
        } else {
            // Create a new order
            $order = new Order();
            $order->user_id = Auth::id();
            $order->isOrdered = false;
            $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            $order->save();

            // Create new order item
            $order_item = new OrderItem();
            $order_item->user_id = Auth::id();
            $order_item->order_id = $order->id;
            $order_item->product_id = $this->product->id;
            $order_item->size_variant_id = $this->size_variant_id;
            $order_item->color_variant_id = $this->color_variant_id;
            $order_item->quantity = $this->quantity;
            $order_item->save();
        }

        session()->flash('success', 'Product added to cart successfully!');
        $this->redirectRoute('public.cart');
    }

    public function buyNow()
    {
        $this->addToCart();
        return $this->redirectRoute('public.checkout');
    }

    public function render()
    {
        return view('livewire.public.product-detail');
    }
}