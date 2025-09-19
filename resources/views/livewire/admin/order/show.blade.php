<div class="container mx-auto ">
    <h1 class="text-3xl font-medium text-[#171717] mb-6">Order #{{ $order->order_number }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg ">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Customer</h3>
            <p class="text-lg font-medium text-[#171717]">{{ $order->user->name ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ $order->user->email ?? 'N/A' }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg ">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Total Amount</h3>
            <p class="text-2xl font-medium text-[#171717]">₹{{ number_format($order->total_amount, 2) }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg ">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Status</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'processing' ? 'bg-blue-100 text-blue-800' : ($order->status == 'shipped' ? 'bg-green-100 text-green-800' : ($order->status == 'delivered' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        
        <div class="bg-white p-6 rounded-lg ">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Payment</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->payment->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($order->payment->payment_status ?? 'unpaid') }}
            </span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg  mb-8">
        <h2 class="text-xl font-medium text-[#171717] mb-4">Update Order Status</h2>
        <form wire:submit="updateStatus" class="flex flex-col md:flex-row gap-4 items-start md:items-center">
            <select wire:model="statusUpdate" class="p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] flex-grow">
                @foreach (['pending', 'processing', 'shipped', 'delivered', 'canceled', 'returned'] as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            
            @if ($statusUpdate === 'canceled')
                <input type="text" wire:model="cancellationReason" placeholder="Cancellation Reason" class="p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] flex-grow" required>
                @error('cancellationReason') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            @endif
            
            <button type="submit" class="bg-[#8f4da7] text-white px-6 py-3 rounded-md shadow hover:bg-[#7a3d92] transition-colors">Update Status</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg  mb-8 overflow-x-auto">
        <h2 class="text-xl font-medium text-[#171717] mb-4">Order Items</h2>
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td class="px-4 py-4 text-sm text-gray-900">{{ $item->product->name ?? 'N/A' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900">{{ $item->quantity }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900">₹{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white p-6 rounded-lg  mb-8">
        <h2 class="text-xl font-medium text-[#171717] mb-4">Shipping & Tracking</h2>
        @if ($order->shiprocket)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">AWB Code</p>
                    <p class="text-lg text-[#171717]">{{ $order->shiprocket->awb_code }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Current Status</p>
                    <p class="text-lg text-[#171717]">{{ ucfirst($order->shiprocket->status) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Estimated Delivery</p>
                    <p class="text-lg text-[#171717]">{{ $order->shiprocket->estimated_delivery_date ?? 'N/A' }}</p>
                </div>
            </div>
            <button wire:click="trackShipment" class="bg-[#8f4da7] text-white px-6 py-2 rounded-md shadow hover:bg-[#7a3d92] transition-colors">Track Shipment</button>
        @else
            <p class="text-gray-600">No shipment. Update status to 'shipped' to create one.</p>
        @endif
    </div>

    <div class="bg-white p-6 rounded-lg ">
        <h2 class="text-xl font-medium text-[#171717] mb-4">Returns & Cancellations</h2>
        <form wire:submit="updateReturnStatus" class="flex flex-col md:flex-row gap-4 items-start md:items-center mb-6">
            <select wire:model="returnStatus" class="p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] flex-grow">
                <option value="">No Return</option>
                @foreach (['requested', 'approved', 'rejected', 'processed'] as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <input type="text" wire:model="returnReason" placeholder="Return Reason" class="p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] flex-grow">
            <button type="submit" class="bg-[#8f4da7] text-white px-6 py-3 rounded-md shadow hover:bg-[#7a3d92] transition-colors">Update Return</button>
        </form>
        
        @if ($order->return_reason)
            <div class="border-t pt-4 mt-4">
                <p class="text-sm font-medium text-gray-500">Return Reason</p>
                <p class="text-[#171717] mb-2">{{ $order->return_reason }}</p>
                <p class="text-sm font-medium text-gray-500">Requested At</p>
                <p class="text-[#171717]">{{ $order->return_requested_at }}</p>
            </div>
        @endif
        
        @if ($order->cancellation_reason)
            <div class="border-t pt-4 mt-4">
                <p class="text-sm font-medium text-gray-500">Cancellation Reason</p>
                <p class="text-[#171717] mb-2">{{ $order->cancellation_reason }}</p>
                <p class="text-sm font-medium text-gray-500">Cancelled At</p>
                <p class="text-[#171717]">{{ $order->cancelled_at }}</p>
            </div>
        @endif
    </div>

    @if (session('message'))
        <div class="mt-6 p-4 bg-green-100 text-green-800 rounded-lg ">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-6 p-4 bg-red-100 text-red-800 rounded-lg ">{{ session('error') }}</div>
    @endif
</div>