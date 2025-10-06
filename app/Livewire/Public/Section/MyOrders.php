<?php

namespace App\Livewire\Public\Section;

use App\Models\Order;
use App\Services\ShiprocketService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('My Order')]
class MyOrders extends Component
{
    use WithPagination;

    public $whatsappNumber;
    public $customizationMessage;
    public $trackingDetails = [];
    public $showTrackingModal = false;
    public $selectedAwbCode = null;
    public $trackingError = null;
    public $showCancelConfirmModal = false;
    public $orderToCancel = null;
    public $cancellationReason = '';

    protected $rules = [
        'cancellationReason' => 'required|string|min:10|max:500'
    ];

    protected $messages = [
        'cancellationReason.required' => 'Please provide a reason for cancellation.',
        'cancellationReason.min' => 'Cancellation reason must be at least 10 characters.',
        'cancellationReason.max' => 'Cancellation reason cannot exceed 500 characters.'
    ];

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

    public function trackShipment($awbCode)
    {
        $this->resetTracking();
        $this->selectedAwbCode = $awbCode;

        if (empty($awbCode)) {
            $this->trackingError = 'Tracking unavailable: No AWB code provided.';
            $this->dispatch('error', message: $this->trackingError);
            return;
        }

        try {
            $service = app(ShiprocketService::class);
            $this->trackingDetails[$awbCode] = $service->trackShipment($awbCode);
            $this->showTrackingModal = true;
            $this->dispatch('open-tracking-modal');
            $this->dispatch('tracking-updated', awbCode: $awbCode);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), 'locked out')
                ? 'Tracking is temporarily unavailable due to API lockout. Please try again later.'
                : (str_contains($e->getMessage(), 'Invalid email and password')
                    ? 'Tracking is unavailable due to authentication issues. Please try again later.'
                    : (str_contains($e->getMessage(), 'Invalid Data')
                        ? 'Tracking unavailable due to invalid shipment data. Contact support.'
                        : (str_contains($e->getMessage(), 'channel id does not exist')
                            ? 'Tracking unavailable due to invalid channel configuration. Contact support.'
                            : (str_contains($e->getMessage(), 'Pickup location')
                                ? 'Tracking unavailable due to invalid pickup location. Contact support.'
                                : (str_contains($e->getMessage(), '404 Not Found')
                                    ? 'Tracking unavailable: Shipment not found.'
                                    : 'Unable to track shipment: ' . $e->getMessage())))));
            $this->trackingError = $message;
            $this->dispatch('error', message: $message);
        }
    }

    public function confirmCancelOrder($orderId)
    {
        $this->orderToCancel = $orderId;
        $this->showCancelConfirmModal = true;
        $this->dispatch('open-cancel-confirm-modal');
    }

    public function cancelOrder()
    {
        if (empty($this->cancellationReason)) {
            $this->addError('cancellationReason', 'Please provide a reason for cancellation.');
            return;
        }

        $order = Order::where('id', $this->orderToCancel)
            ->where('user_id', Auth::id())
            ->with(['shiprocketOrder', 'payment'])
            ->first();

        if (!$order) {
            $this->dispatch('error', message: 'Order not found or you are not authorized to cancel it.');
            $this->resetCancel();
            return;
        }

        try {
            // Check if order can be cancelled
            if (!$order->canBeCancelled()) {
                throw new \Exception('Order cannot be canceled as it is ' . $order->status . '.');
            }

            // Cancel order and process refund if necessary
            $order->cancel($this->cancellationReason);
            $this->dispatch('success', message: 'Order #' . $order->order_number . ' has been canceled successfully.');
            $this->resetCancel();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to cancel order: ' . $e->getMessage());
            $this->resetCancel();
        }
    }

    public function resetTracking()
    {
        $this->trackingError = null;
        $this->selectedAwbCode = null;
        $this->showTrackingModal = false;
    }

    public function resetCancel()
    {
        $this->orderToCancel = null;
        $this->showCancelConfirmModal = false;
        $this->cancellationReason = '';
        $this->resetErrorBag('cancellationReason');
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product.images', 'orderItems.product.brand', 'orderItems.product.highlights', 'address', 'shiprocketOrder'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.public.section.my-orders', [
            'orders' => $orders,
        ]);
    }
}