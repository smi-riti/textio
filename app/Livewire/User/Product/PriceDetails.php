<?php

namespace App\Livewire\User\Product;

use Livewire\Component;

class PriceDetails extends Component
{

    public $product;
    public $quantity = 1;
    public $price = 0;
    public $discount = 0;
    public $deliveryCharge = 0;
    
    protected $listeners = ['cartUpdated' => 'updatePriceDetails'];
    
    public function mount($product, $quantity, $price, $discount, $deliveryCharge)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->discount = $discount;
        $this->deliveryCharge = $deliveryCharge;
    }
    
    public function updatePriceDetails($cartItem)
    {
        // Update details if needed when cart is updated
        $this->quantity = $cartItem['quantity'];
    }
    
    public function getTotalPriceProperty()
    {
        return ($this->price - $this->discount) * $this->quantity + $this->deliveryCharge;
    }
    
    public function getTotalDiscountProperty()
    {
        return $this->discount * $this->quantity;
    }
    
    public function render()
    {
        return view('livewire.user.product.price-details');
    }
}
