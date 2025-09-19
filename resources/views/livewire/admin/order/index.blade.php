<div>

    <div class="container mx-auto ">
        <h1 class="text-3xl mb-6 text-primary">Order Management</h1>

        <!-- Filter Section -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    <input type="text" wire:model.live="search" placeholder="Search by order number or customer name..." class="w-full pl-10 p-2 border rounded-md focus:ring-2 focus:ring-accent focus:border-transparent">
                </div>
                <div class="relative">
                    <select wire:model.live="status" class="w-full p-2 border rounded-md appearance-none focus:ring-2 focus:ring-accent focus:border-transparent pr-8">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm uppercase tracking-wider text-gray-500">Order Number</th>
                            <th class="px-6 py-3 text-left text-sm uppercase tracking-wider text-gray-500">Customer</th>
                            <th class="px-6 py-3 text-left text-sm uppercase tracking-wider text-gray-500">Total</th>
                            <th class="px-6 py-3 text-left text-sm uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-sm uppercase tracking-wider text-gray-500">Payment</th>
                            <th class="px-6 py-3 text-left text-sm uppercase tracking-wider text-gray-500">Shipping</th>
                            <th class="px-6 py-3 text-left text-sm uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_number }}</td>
                                <td class="px-6 py-4">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">₹{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'processing' ? 'bg-blue-100 text-blue-800' : ($order->status == 'shipped' ? 'bg-green-100 text-green-800' : ($order->status == 'delivered' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($order->payment->payment_status ?? 'unpaid') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($order->shiprocket)
                                        <span class="text-sm text-accent flex items-center">
                                            <i class="fas fa-truck mr-1"></i>
                                            {{ $order->shiprocket->awb_code ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Not Shipped
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a wire:navigate href="{{ route('admin.orders.show', $order->id) }}" class="text-accent hover:text-purple-700 transition-colors flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $orders->links() }}
        </div>
    </div>

    <style>
        /* Custom pagination styling to match our color scheme */
        .pagination span, .pagination a {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin: 0 0.15rem;
            border-radius: 0.25rem;
            border: 1px solid #e5e7eb;
            color: #171717;
            transition: all 0.2s;
        }
        
        .pagination a:hover {
            background-color: #f3e8f7;
            border-color: #8f4da7;
        }
        
        .pagination span.current {
            background-color: #8f4da7;
            border-color: #8f4da7;
            color: white;
        }
        
        /* Form styling */
        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(143, 77, 167, 0.2);
        }
        
        /* Table row hover effect */
        tr {
            transition: background-color 0.2s ease;
        }
    </style>

</div>