<?php

namespace App\Livewire\User\Product;

use Livewire\Component;
use App\Models\Product;

class Show extends Component
{

     public $product;
    public $quantity = 1;
    public $selectedColor = '';
    public $selectedStorage = '';
    
    public function mount($id)
    {
        $this->product = Product::with('images', 'variants')->findOrFail($id);
        
        // Set default selections if variants exist
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
    
    public function addToCart()
    {
        $cartItem = [
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
            'color' => $this->selectedColor,
            'storage' => $this->selectedStorage,
        ];
        
        // Dispatch event or add to session
        $this->dispatch('cartUpdated', $cartItem);
        session()->flash('message', 'Product added to cart successfully!');
    }
    
    public function render()
    {
        return view('livewire.user.product.show');
    }
}
