<?php

namespace App\Livewire\Public\Section;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyCart extends Component
{
    public $cartItems = [];

    public function mount()
    {
        $this->loadCartItems();
    }

    public function loadCartItems()
    {
        if (Auth::check()) {
            $this->cartItems = Cart::with(['product', 'productVariant'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $this->cartItems = collect(); // Empty collection if user is not authenticated
        }
    }

    public function increaseQuantity($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->increment('quantity');
            $this->loadCartItems(); // Refresh cart items
        }
    }

    public function decreaseQuantity($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id() && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
            $this->loadCartItems(); // Refresh cart items
        }
    }

    public function removeItem($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->delete();
            $this->loadCartItems(); // Refresh cart items
        }
    }

   public function placeOrder()
    {
        if ($this->cartItems->isNotEmpty()) {
            // Calculate total amount
            $totalAmount = $this->cartItems->sum(function ($item) {
                // Assume discount_price comes from product or variant
                $price = $item->product->discount_price ?? $item->product->price;
                return $price * $item->quantity;
            });

            // Store cart items in session
            session()->put('pending_order', [
                'cartItems' => $this->cartItems->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'color_variant_id' => $item->color_variant_id,
                        'size_variant_id' => $item->size_variant_id,
                        'quantity' => $item->quantity,
                        'product' => [
                            'name' => $item->product->name,
                            'price' => $item->product->price,
                            'discount_price' => $item->product->discount_price,
                            'image' => $item->product->image,
                        ],
                        'colorVariant' => $item->colorVariant ? ['variant_name' => $item->colorVariant->variant_name] : null,
                        'sizeVariant' => $item->sizeVariant ? ['variant_name' => $item->sizeVariant->variant_name] : null,
                    ];
                })->toArray(),
                'total_amount' => $totalAmount,
                'user_email' => Auth::user()->email,
                'address_id' => $address_id ?? null , // Replace with actual address_id logic
            ]);
            return redirect()->route('myOrder');
        }
        session()->flash('error', 'Cart is empty!');
    }

    public function render()
    {
        return view('livewire.public.section.my-cart');
    }
}