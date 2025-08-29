<?php

namespace App\Livewire\User\Product;

use Livewire\Component;

class PriceDetails extends Component
{
    public $product;
    public $quantity;
    public $price;
    public $discount;
    public $deliveryCharge;

    protected $listeners = ['quantityUpdated' => 'updateQuantity'];

    public function mount($product, $quantity, $price, $discount, $deliveryCharge)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->discount = $discount;
        $this->deliveryCharge = $deliveryCharge;
    }

    public function updateQuantity($data)
    {
        $this->quantity = $data['quantity'];
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
        return view('livewire.user.product.price-details', [
            'totalPrice' => $this->getTotalPriceProperty(),
            'totalDiscount' => $this->getTotalDiscountProperty(),
        ]);
    }
}