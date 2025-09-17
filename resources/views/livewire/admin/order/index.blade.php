<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Order Management</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" wire:model.live="search" placeholder="Search by order number or customer name..." class="flex-1 p-2 border rounded">
            <select wire:model.live="status" class="p-2 border rounded">
                <option value="">All Statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Order Number</th>
                    <th class="px-6 py-3 text-left">Customer</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Payment</th>
                    <th class="px-6 py-3 text-left">Shipping</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b">
                        <td class="px-6 py-4">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'processing' ? 'bg-blue-100 text-blue-800' : ($order->status == 'shipped' ? 'bg-green-100 text-green-800' : ($order->status == 'delivered' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs {{ $order->payment->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($order->payment->payment_status ?? 'unpaid') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($order->shiprocket)
                                <span class="text-sm text-blue-600">{{ $order->shiprocket->awb_code ?? 'N/A' }}</span>
                            @else
                                <span class="text-sm text-gray-500">Not Shipped</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>