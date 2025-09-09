<?php

namespace App\Livewire\Public\Section;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;

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
    public $couponCode = '';
    public $discountAmount = 0;

    public function rules()
    {
        return [
            'addressId' => 'required|exists:addresses,id,user_id,' . Auth::id(),
            'paymentMethod' => 'required|in:Cash on Delivery,UPI',
        ];
    }

    public function mount()
    {
        $this->whatsappNumber = env('WHATSAPP_NUMBER', '+1234567890');
        $this->customizationMessage = env('WHATSAPP_CUSTOMIZATION_MESSAGE', 'Hi! I\'m interested in customizing this product:');

        $this->pendingOrder = session()->get('pending_order', []);
        if (empty($this->pendingOrder)) {
            session()->flash('error', 'No order data found.');
            return redirect()->route('myCart');
        }

        // Validate and fix the cart items structure
        $this->cartItems = collect($this->pendingOrder['cartItems'])->map(function ($item) {
            // Ensure product array has both price and discount_price
            if (!isset($item['product']['discount_price'])) {
                $item['product']['discount_price'] = $item['product']['price'] ?? 0;
            }
            if (!isset($item['product']['price'])) {
                $item['product']['price'] = $item['product']['discount_price'] ?? 0;
            }
            return $item;
        });

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

    #[On('coupon-applied')]
    public function updateCoupon($couponCode, $discount)
    {
        $this->discountAmount = $discount;
        $this->couponCode = $couponCode;
        $this->totalAmount = max(0, $this->pendingOrder['total_amount'] - $this->discountAmount);
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

        $this->validate();

        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $this->addressId,
            'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . Str::random(6),
            'isOrdered' => true,
            'status' => 'pending',
            'total_amount' => number_format($this->totalAmount, 2, '.', ''),
            'coupon_code' => $this->couponCode ?: null,
            'shipping_charge' => 0.00,
            'payment_status' => $this->paymentMethod === 'Cash on Delivery' ? 'unpaid' : 'pending',
            'payment_method' => $this->paymentMethod,
        ]);

        foreach ($this->cartItems as $item) {
            $orderItemData = [
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ];

            // Handle variant details if available
           if (!empty($item['variant_details'])) {
    if (is_array($item['variant_details']) || is_object($item['variant_details'])) {
        foreach ($item['variant_details'] as $variant) {
            if (is_array($variant) || is_object($variant)) {
                foreach ($variant as $type => $value) {
                    if ($type === 'Color') {
                        $orderItemData['color'] = $value;
                    } elseif ($type === 'Size') {
                        $orderItemData['size'] = $value;
                    }
                }
            }
        }
    } else {
        \Log::error("variant_details is not an array or object: " . json_encode($item['variant_details']));
    }
}


            OrderItem::create($orderItemData);
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