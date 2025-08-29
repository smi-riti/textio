<?php

namespace App\Livewire\User\Product;

use Livewire\Component;
use App\Models\Product;

class AddItem extends Component
{
    public $product;
    public $selectedColor = '';
    public $selectedStorage = '';
    public $quantity = 1;
    public $price = 0;
    public $discount = 0;
    public $deliveryCharge = 0;

    public function mount($slug, $quantity, $selectedColor, $selectedStorage)
    {
        $this->product = Product::with(['images', 'variants'])->where('slug', $slug)->firstOrFail();
        $this->price = $this->product->price;
        $this->discount = $this->product->discount;
        $this->deliveryCharge = $this->product->delivery_charge;
        $this->quantity = $quantity;
        $this->selectedColor = $selectedColor;
        $this->selectedStorage = $selectedStorage;

        // Set default variant selections if not provided
        if ($this->product->variants->isNotEmpty()) {
            $colorVariant = $this->product->variants->where('type', 'color')->first();
            $storageVariant = $this->product->variants->where('type', 'storage')->first();
            if ($colorVariant && !$this->selectedColor) {
                $this->selectedColor = $colorVariant->value;
            }
            if ($storageVariant && !$this->selectedStorage) {
                $this->selectedStorage = $storageVariant->value;
            }
        }
    }

    public function increment()
    {
        $this->quantity++;
        $this->dispatch('quantityUpdated', ['quantity' => $this->quantity]);
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->dispatch('quantityUpdated', ['quantity' => $this->quantity]);
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

    public function addToCart()
    {
        $cartItem = [
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
            'color' => $this->selectedColor,
            'storage' => $this->selectedStorage,
        ];

        $this->dispatch('cartUpdated', $cartItem)->to('view-product');
    }

     public function backToProduct()
    {
      $this->dispatch('backToProduct')->to('public.section.view-product');
    }

    public function render()
    {
        return view('livewire.user.product.add-item', [
            'totalPrice' => ($this->price - $this->discount) * $this->quantity + $this->deliveryCharge,
            'totalDiscount' => $this->discount * $this->quantity,
        ]);
    }
}