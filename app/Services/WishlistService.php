<?php

namespace App\Services;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    public function addToWishlist(int $productId): array
    {
        if (!Auth::check()) {
            return [
                'success' => false,
                'message' => 'Please log in to add items to your wishlist.',
                'redirect' => route('login')
            ];
        }

        $userId = Auth::id();
        $exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->exists();

        if ($exists) {
            return ['success' => false, 'message' => 'Product already in wishlist.'];
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return ['success' => true, 'message' => 'Product added to wishlist!'];
    }

    public function removeFromWishlist(int $productId): array
    {
        if (!Auth::check()) {
            return [
                'success' => false,
                'message' => 'Please log in to manage your wishlist.',
                'redirect' => route('login')
            ];
        }

        $deleted = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->delete();

        if (!$deleted) {
            return ['success' => false, 'message' => 'Product not found in wishlist.'];
        }

        return ['success' => true, 'message' => 'Product removed from wishlist.'];
    }

  public function isInWishlist(int $productId): bool
{
    static $wishlistProductIds = null;

    if (!Auth::check()) {
        return false;
    }

    // Load wishlist product IDs only once per request
    if ($wishlistProductIds === null) {
        $wishlistProductIds = Wishlist::where('user_id', Auth::id())
            ->pluck('product_id')
            ->toArray();
    }

    return in_array($productId, $wishlistProductIds);
}


    public function getWishlistItems()
    {
        if (!Auth::check()) {
            return collect();
        }

        return Wishlist::where('user_id', Auth::id())->with('product')->get();
    }
}