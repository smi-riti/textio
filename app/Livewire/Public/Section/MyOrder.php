<?php

namespace App\Livewire\Public\Section;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class MyOrder extends Component
{
    public $pendingOrder;
    public $cartItems;
    public $totalAmount;
    public $userEmail;
    public $addressId;
    public $addresses;
    public $paymentMethod = 'Cash on Delivery';
    public $whatsappNumber;
    public $customizationMessage;

    public function mount()
    {
        $this->whatsappNumber = env('WHATSAPP_NUMBER', '+1234567890');
        $this->customizationMessage = env('WHATSAPP_CUSTOMIZATION_MESSAGE', 'Hi! I\'m interested in customizing this product:');

        $this->pendingOrder = session()->get('pending_order', []);
        if (empty($this->pendingOrder)) {
            session()->flash('error', 'No order data found.');
            return redirect()->route('myCart');
        }

        $this->cartItems = collect($this->pendingOrder['cartItems']);
        $this->totalAmount = $this->pendingOrder['total_amount'];
        $this->userEmail = $this->pendingOrder['user_email'];
        $this->addressId = $this->pendingOrder['address_id'] ?? null;

        $this->loadUserAddresses();
    }

    private function loadUserAddresses()
    {
        $this->addresses = Address::where('user_id', Auth::id())->get();
        $latestOrder = Order::where('user_id', Auth::id())->latest()->first();
        $this->addressId = $latestOrder?->address_id ?? $this->addresses->first()->id ?? null;
    }

    #[On('address-updated')]
    public function refreshAddresses()
    {
        $this->loadUserAddresses();
    }

    public function confirmOrder()
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to place an order.');
            return redirect()->route('login');
        }

        if ($this->cartItems->isEmpty()) {
            session()->flash('error', 'Cart is empty.');
            return redirect()->route('myCart');
        }

        if (!$this->addressId || !Address::where('user_id', Auth::id())->where('id', $this->addressId)->exists()) {
            session()->flash('error', 'Please select a valid delivery address.');
            return;
        }

        if (!in_array($this->paymentMethod, ['Cash on Delivery', 'UPI'])) {
            session()->flash('error', 'Please select a valid payment method.');
            return;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $this->addressId,
            'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . Str::random(6),
            'isOrdered' => true,
            'status' => 'pending',
            'total_amount' => number_format($this->totalAmount, 2, '.', ''),
            'shipping_charge' => 0.00,
            'payment_status' => $this->paymentMethod === 'Cash on Delivery' ? 'unpaid' : 'pending',
            'payment_method' => $this->paymentMethod,
            'coupon_code' => null,
        ]);

        foreach ($this->cartItems as $item) {
            OrderItem::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'color_variant_id' => $item['color_variant_id'] ?? null,
                'size_variant_id' => $item['size_variant_id'] ?? null,
                'quantity' => $item['quantity'],
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();
        session()->forget('pending_order');

        session()->flash('message', 'Order placed successfully! Order Number: ' . $order->order_number);
        return redirect()->route('myOrders');
    }

    public function getCustomizationWhatsappUrl($productName, $orderNumber = null)
    {
        $message = $this->customizationMessage . " " . $productName;
        if ($orderNumber) {
            $message .= " (Order: " . $orderNumber . ")";
        }
        $message .= " - " . url()->current();
        $encodedMessage = urlencode($message);
        return "https://wa.me/" . str_replace(['+', ' ', '-'], '', $this->whatsappNumber) . "?text=" . $encodedMessage;
    }

    public function render()
    {
        return view('livewire.public.section.my-order', [
            'addresses' => $this->addresses,
        ]);
    }
}