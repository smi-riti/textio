<?php

namespace App\Livewire\Public\Section;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class MyOrders extends Component
{
    use WithPagination;

    public $whatsappNumber;
    public $customizationMessage;

    public function mount()
    {
        $this->whatsappNumber = env('WHATSAPP_NUMBER', '+1234567890');
        $this->customizationMessage = env('WHATSAPP_CUSTOMIZATION_MESSAGE', 'Hi! I\'m interested in customizing this product:');
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
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product.images', 'orderItems.product.brand', 'orderItems.product.highlights', 'address'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.public.section.my-orders', [
            'orders' => $orders,
        ]);
    }
}
