<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Order #{{ $order->order_number }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 rounded-lg  ">
            <h3 class="text-sm font-medium text-gray-500">Customer</h3>
            <p class="text-lg font-bold">{{ $order->user->name ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600">{{ $order->user->email ?? 'N/A' }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg  ">
            <h3 class="text-sm font-medium text-gray-500">Total Amount</h3>
            <p class="text-2xl font-bold">₹{{ number_format($order->total_amount, 2) }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg  ">
            <h3 class="text-sm font-medium text-gray-500">Status</h3>
            <span class="px-3 py-1 rounded-full text-sm {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'processing' ? 'bg-blue-100 text-blue-800' : ($order->status == 'shipped' ? 'bg-green-100 text-green-800' : ($order->status == 'delivered' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <div class="bg-white p-4 rounded-lg  ">
            <h3 class="text-sm font-medium text-gray-500">Payment</h3>
            <span class="px-3 py-1 rounded-full text-sm {{ $order->payment->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($order->payment->payment_status ?? 'unpaid') }}
            </span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg   mb-6">
        <h2 class="text-xl font-bold mb-4">Update Order Status</h2>
        <form wire:submit="updateStatus">
            <select wire:model="statusUpdate" class="p-2 border rounded mr-4">
                @foreach (['pending', 'processing', 'shipped', 'delivered', 'canceled', 'returned'] as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            @if ($statusUpdate === 'canceled')
                <input type="text" wire:model="cancellationReason" placeholder="Cancellation Reason" class="p-2 border rounded mr-4" required>
                @error('cancellationReason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @endif
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Status</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg   mb-6">
        <h2 class="text-xl font-bold mb-4">Order Items</h2>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Product</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Price</th>
                    <th class="px-4 py-2 text-left">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->product->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-2">₹{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white p-6 rounded-lg   mb-6">
        <h2 class="text-xl font-bold mb-4">Shipping & Tracking</h2>
        @if ($order->shiprocket)
        <p><strong>AWB Code:</strong> {{ $order->shiprocket->awb_code }}</p>
        <p><strong>Current Status:</strong> {{ ucfirst($order->shiprocket->status) }}</p>
        <p><strong>Estimated Delivery:</strong> {{ $order->shiprocket->estimated_delivery_date ?? 'N/A' }}</p>
        <button wire:click="trackShipment" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Track Shipment</button>
    @else
        <p>No shipment. Update status to 'shipped' to create one.</p>
    @endif
    </div>

    <div class="bg-white p-6 rounded-lg  ">
        <h2 class="text-xl font-bold mb-4">Returns & Cancellations</h2>
        <form wire:submit="updateReturnStatus">
            <select wire:model="returnStatus" class="p-2 border rounded mr-4">
                <option value="">No Return</option>
                @foreach (['requested', 'approved', 'rejected', 'processed'] as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <input type="text" wire:model="returnReason" placeholder="Return Reason" class="p-2 border rounded mr-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Return</button>
        </form>
        @if ($order->return_reason)
            <p class="mt-4"><strong>Return Reason:</strong> {{ $order->return_reason }}</p>
            <p><strong>Requested At:</strong> {{ $order->return_requested_at }}</p>
        @endif
        @if ($order->cancellation_reason)
            <p class="mt-4"><strong>Cancellation Reason:</strong> {{ $order->cancellation_reason }}</p>
            <p><strong>Cancelled At:</strong> {{ $order->cancelled_at }}</p>
        @endif
    </div>

    @if (session('message'))
        <div class="mt-4 p-4 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
</div>