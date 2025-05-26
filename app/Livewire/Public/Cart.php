<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $order;
    public $orderItems = [];

    public function mount()
    {
        if (Auth::check()) {
            $this->order = Order::where([
                ['user_id', Auth::id()],
                ['isOrdered', false],
            ])->with('orderItems.product.images', 'orderItems.sizeVariant', 'orderItems.colorVariant')->first();
            
            if ($this->order) {
                foreach ($this->order->orderItems as $item) {
                    $this->orderItems[$item->id] = ['quantity' => $item->quantity];
                }
            }
        }
    }

    public function updateQuantity($itemId, $quantity)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please log in to update your cart.');
            return $this->redirectRoute('login');
        }

        $item = OrderItem::where([
            ['id', $itemId],
            ['user_id', Auth::id()],
        ])->first();

        if ($item && $quantity >= 1) {
            $item->quantity = $quantity;
            $item->save();
            session()->flash('success', 'Quantity updated successfully!');
        }
    }

    public function removeItem($itemId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please log in to update your cart.');
            return $this->redirectRoute('login');
        }

        $item = OrderItem::where([
            ['id', $itemId],
            ['user_id', Auth::id()],
        ])->first();

        if ($item) {
            $item->delete();
            session()->flash('success', 'Item removed from cart successfully!');
            $this->mount(); // Refresh the order data
        }
    }

    public function getTotal()
    {
        if (!$this->order) {
            return 0;
        }

        return $this->order->orderItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function render()
    {
        return view('livewire.public.cart');
    }
}