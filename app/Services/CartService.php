<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function addToCart($productId, $quantity = 1, $productVariantCombinationId = null)
    {
        if (!Auth::check()) {
            return [
                'success' => false,
                'redirect' => route('login'),
                'message' => 'Please log in to add items to your cart.'
            ];
        }

        $userId = Auth::id();

        // Check if the item already exists in the cart
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('product_variant_combination_id', $productVariantCombinationId) // Updated column name
            ->first();

        if ($cartItem) {
            // Update quantity if item exists
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'product_variant_combination_id' => $productVariantCombinationId, // Updated column name
                'quantity' => $quantity,
            ]);
        }

        return [
            'success' => true,
            'message' => 'Item added to cart successfully!'
        ];
    }
}