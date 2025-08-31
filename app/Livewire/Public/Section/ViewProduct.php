<?php

namespace App\Livewire\Public\Section;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant; // Add this to query variants
use App\Services\CartService;

class ViewProduct extends Component
{
    public $product;
    public $slug;
    public $relatedProducts;
    public $quantity = 1;
    public $selectedColor = '';
    public $selectedStorage = '';

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->product = Product::with(['category', 'images', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->with('images')
            ->take(5)
            ->get();

        // Set default variant selections
        if ($this->product->variants->isNotEmpty()) {
            $colorVariant = $this->product->variants->where('type', 'color')->first();
            $storageVariant = $this->product->variants->where('type', 'storage')->first();
            if ($colorVariant) {
                $this->selectedColor = $colorVariant->value;
            }
            if ($storageVariant) {
                $this->selectedStorage = $storageVariant->value;
            }
        }
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

    public function addToCart($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to add items to your cart.');
        }

        // Resolve CartService
        $cartService = app(CartService::class);

        // Find the product variant ID based on selected color and storage
        $productVariantId = null;
        if ($this->selectedColor || $this->selectedStorage) {
            $query = ProductVariant::where('product_id', $productId);

            if ($this->selectedColor) {
                $query->where(function ($q) {
                    $q->where('type', 'color')->where('value', $this->selectedColor);
                });
            }

            if ($this->selectedStorage) {
                $query->where(function ($q) {
                    $q->where('type', 'storage')->where('value', $this->selectedStorage);
                });
            }

            $productVariant = $query->first();

            if (!$productVariant) {
                $this->dispatch('notify', ['message' => 'Selected variant combination is not available.', 'type' => 'error']);
                return redirect()->route('public.product.view', $this->slug)
                    ->with('error', 'Selected variant combination is not available.');
            }

            $productVariantId = $productVariant->id;
        }

        // Add to cart with quantity and product variant ID
        $result = $cartService->addToCart($productId, $this->quantity, $productVariantId);

        if ($result['success']) {
            $this->dispatch('notify', ['message' => $result['message'], 'type' => 'success']);
            $this->dispatch('cartUpdated');
            return redirect()->route('public.cart')->with('success', $result['message']);
        }

        return redirect()->to($result['redirect'] ?? route('public.product.view', $this->slug))
            ->with('error', $result['message']);
    }

    public function render()
    {
        return view('livewire.public.section.view-product', [
            'price' => $this->product->price,
            'discount' => $this->product->discount_price ?? 0,
            'deliveryCharge' => $this->product->delivery_charge ?? 0,
            'quantity' => $this->quantity,
        ]);
    }
}