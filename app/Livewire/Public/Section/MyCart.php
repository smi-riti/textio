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
            $this->cartItems = Cart::with(['product.images', 'variantCombination'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $this->cartItems = collect();
        }
    }

    public function increaseQuantity($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->increment('quantity');
            $this->loadCartItems();
        }
    }

    public function decreaseQuantity($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id() && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
            $this->loadCartItems();
        }
    }

    public function removeItem($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->delete();
            $this->loadCartItems();
        }
    }

    public function placeOrder()
    {
        if ($this->cartItems->isNotEmpty()) {
            // Calculate total amount with variant pricing
            $totalAmount = $this->cartItems->sum(function ($item) {
                $price = $this->getItemPrice($item);
                return $price * $item->quantity;
            });

            // Store cart items in session
            session()->put('pending_order', [
                'cartItems' => $this->cartItems->map(function ($item) {
                    $price = $this->getItemPrice($item);
                    $regularPrice = $this->getItemRegularPrice($item);

                    return [
                        'product_id' => $item->product_id,
                        'product_variant_combination_id' => $item->product_variant_combination_id,
                        'quantity' => $item->quantity,
                        'product' => [
                            'name' => $item->product->name,
                            'price' => $regularPrice,
                            'discount_price' => $price,
                            'image' => $item->product->images->first()->image_path ?? null,
                        ],
                        'variant_details' => $item->variantCombination
                            ? (is_string($item->variantCombination->variant_values)
                                ? json_decode($item->variantCombination->variant_values, true)
                                : $item->variantCombination->variant_values)
                            : null,
// dd($variant_details);
                    ];
                })->toArray(),
                'total_amount' => $totalAmount,
                'user_email' => Auth::user()->email,
                'address_id' => null,
            ]);

            return redirect()->route('myOrder');
        }

        session()->flash('error', 'Cart is empty!');
    }

    // Helper method to get the correct price for an item
    private function getItemPrice($item)
    {
        if ($item->variantCombination && $item->variantCombination->price) {
            return $item->variantCombination->price;
        }

        return $item->product->discount_price ?? $item->product->price;
    }

    // Helper method to get the regular price for discount calculation
    private function getItemRegularPrice($item)
    {

        if ($item->variantCombination) {
            // If variant has a regular price, use it, otherwise use product price
            return $item->variantCombination->regular_price ?? $item->product->price;
        }

        return $item->product->price;

    }



    // Calculate discount percentage for an item
    private function getItemDiscountPercentage($item)
    {
        $price = $this->getItemPrice($item);
        $regularPrice = $this->getItemRegularPrice($item);

        if ($regularPrice > 0 && $price < $regularPrice) {
            return round((($regularPrice - $price) / $regularPrice) * 100);
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.public.section.my-cart');
    }
}