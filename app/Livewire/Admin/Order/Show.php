<?php

namespace App\Livewire\Admin\Order;

use App\Models\Order;
use App\Services\ShiprocketService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

use Livewire\Attributes\Layout;
#[Layout('components.layouts.admin')]
class Show extends Component
{
    public Order $order;
    public $statusUpdate = '';
    public $returnStatus = '';
    public $returnReason = '';
    public $cancellationReason = '';

    public function mount($id)
    {
        $this->order = Order::with(['user', 'address', 'orderItems.product', 'payment', 'shiprocket'])->findOrFail($id);
        $this->statusUpdate = $this->order->status;
        $this->returnStatus = $this->order->return_status ?? '';
    }
   // In App\Livewire\Admin\Order\Show.php
public function updateStatus()
{
    $this->validate(['statusUpdate' => 'required|in:pending,processing,shipped,delivered,canceled,returned']);

    if ($this->statusUpdate === 'canceled' && !$this->cancellationReason) {
        $this->addError('cancellationReason', 'Cancellation reason is required.');
        return;
    }

    $oldStatus = $this->order->status;
    $this->order->update([
        'status' => $this->statusUpdate,
        'cancelled_at' => $this->statusUpdate === 'canceled' ? now() : null,
        'cancellation_reason' => $this->statusUpdate === 'canceled' ? $this->cancellationReason : null,
    ]);

    // If cancelling and shipment exists, cancel in Shiprocket
    if ($this->statusUpdate === 'canceled' && $this->order->shiprocket) {
        try {
            $service = new ShiprocketService();
            $service->cancelShipment($this->order->shiprocket->shipment_id);
            session()->flash('message', 'Order cancelled and shipment cancelled in Shiprocket.');
        } catch (\Exception $e) {
            Log::error('Shiprocket cancellation error: ' . $e->getMessage());
            session()->flash('error', 'Local cancellation succeeded, but Shiprocket cancel failed: ' . $e->getMessage());
        }
    }

    // If shipping and no shipment, create in Shiprocket
    if ($this->statusUpdate === 'shipped' && !$this->order->shiprocket) {
        try {
            $service = new ShiprocketService();
            $service->createShipment($this->order);
            session()->flash('message', 'Order shipped and shipment created in Shiprocket.');
        } catch (\Exception $e) {
            session()->flash('error', 'Local status updated, but shipping failed: ' . $e->getMessage());
        }
    }

    session()->flash('message', 'Order status updated.');
    $this->reset(['cancellationReason']);
}
    public function updateReturnStatus()
    {
        $this->validate([
            'returnStatus' => 'nullable|in:requested,approved,rejected,processed',
            'returnReason' => 'nullable|string|max:255',
        ]);

        $this->order->update([
            'return_status' => $this->returnStatus,
            'return_reason' => $this->returnReason,
            'return_requested_at' => $this->returnStatus === 'requested' ? now() : $this->order->return_requested_at,
        ]);

        // Optional: If return approved, update payment/refund status
        if ($this->returnStatus === 'approved') {
            $this->order->payment->update(['payment_status' => 'refunded']);
        }

        session()->flash('message', 'Return status updated.');
        $this->reset(['returnReason']);
    }

    public function trackShipment()
    {
        if (!$this->order->shiprocket) {
            session()->flash('error', 'No shipment found. Create one first.');
            return;
        }

        try {
            $service = new ShiprocketService();
            $trackingData = $service->trackShipment($this->order->shiprocket->awb_code);

            // Reload order to show updated status
            $this->order->refresh();

            session()->flash('message', 'Shipment tracked. Latest status: ' . ucfirst($trackingData['shipment_status'] ?? 'Unknown'));
        } catch (\Exception $e) {
            session()->flash('error', 'Tracking error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.order.show')
        ;
    }
}






