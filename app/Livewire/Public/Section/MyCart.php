<?php

namespace App\Livewire\Public\Section;

use App\Models\Cart;
use App\Models\ProductVariantCombination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Cart')]
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

    public function getAvailableStockProperty()
    {
        // This is a computed property that returns an array of available stock for each cart item
        // Keyed by cart item ID
        return $this->cartItems->mapWithKeys(function ($item) {
            $stock = 0;
            if ($item->product_variant_combination_id) {
                // Use the already loaded relationship to avoid N+1 queries
                $combination = $item->variantCombination;
                $stock = $combination ? $combination->stock : 0;
            } else {
                $stock = $item->product->stock ?? 0; // Fallback to product stock; adjust if no 'stock' field
            }
            $remaining = $stock - $item->quantity;
            \Log::info("Stock check for item {$item->id}: Total stock={$stock}, Quantity={$item->quantity}, Remaining={$remaining}");
            return [$item->id => $remaining];
        })->toArray();
    }

    public function increaseQuantity($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $availableStock = $this->availableStock[$cartItemId] ?? 0;  // Access as property
            if ($availableStock <= 0) {
                $this->dispatch('notify', ['message' => 'Stock not available for this item.', 'type' => 'error']);
                return;
            }
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

    public function getAvailableStock($itemId)
{
    $item = $this->cartItems->find($itemId);
    if (!$item) return 0;

    $stock = 0;
    if ($item->product_variant_combination_id) {
        $combination = $item->variantCombination;
        $stock = $combination ? $combination->stock : 0;
    } else {
        $stock = $item->product->stock ?? 0;
    }
    return $stock - $item->quantity;
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