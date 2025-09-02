<?php

namespace App\Livewire\Public\Section;

use App\Models\Address;
use App\Models\OrderItem;
use Livewire\Component;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MyOrder extends Component
{
    public $pendingOrder;
    public $cartItems;
    public $totalAmount;
    public $userEmail;
    public $addressId;
    public $address;
    public $name;
    public $phone;
    public $address_type;
    public $address_line;
    public $city;
    public $state;
    public $postal_code;
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

        // Load address: prioritize address from the most recent order, then first address, or null
        $this->loadUserAddress();
    }

    private function loadUserAddress()
    {
        // Check if user has placed any orders
        $latestOrder = Order::where('user_id', Auth::id())->latest()->first();

        if ($latestOrder && $latestOrder->address_id) {
            // Use address from the most recent order
            $this->address = Address::where('user_id', Auth::id())
                ->where('id', $latestOrder->address_id)
                ->first();
        } else {
            // If no orders, get the first address for the user
            $this->address = Address::where('user_id', Auth::id())->first();
        }

        if ($this->address) {
            $this->addressId = $this->address->id;
            // Initialize form fields with address data
            $this->name = $this->address->name;
            $this->phone = $this->address->phone;
            $this->address_type = $this->address->address_type;
            $this->address_line = $this->address->address_line;
            $this->city = $this->address->city;
            $this->state = $this->address->state;
            $this->postal_code = $this->address->postal_code;
        } else {
            $this->addressId = null; // No address found
        }
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

        // Ensure a valid address is selected
        if (!$this->addressId || !Address::where('user_id', Auth::id())->where('id', $this->addressId)->exists()) {
            session()->flash('error', 'Please add or select a valid delivery address.');
            return redirect()->back();
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $this->addressId,
            'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . Str::random(6),
            'isOrdered' => true,
            'status' => 'pending',
            'total_amount' => number_format($this->totalAmount, 2, '.', ''),
            'shipping_charge' => 0.00,
            'payment_status' => 'unpaid',
            'payment_method' => 'Cash on Delivery',
            'coupon_code' => null,
        ]);

        // Store order items
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

        // Clear cart
        Cart::where('user_id', Auth::id())->delete();

        // Clear session
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

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_type' => 'nullable|string|max:50',
            'address_line' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $address = Address::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address_type' => $validated['address_type'],
            'address_line' => $validated['address_line'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
            'status' => true,
        ]);

        $this->addressId = $address->id;
        $this->address = $address;

        session()->flash('success', 'Address added successfully!');
    }

    public function update($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_type' => 'nullable|string|max:50',
            'address_line' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $address->update($validated);
        $this->address = $address;

        session()->flash('success', 'Address updated successfully!');
    }

    public function render()
    {
        return view('livewire.public.section.my-order', [
            'address' => $this->address,
        ]);
    }
}