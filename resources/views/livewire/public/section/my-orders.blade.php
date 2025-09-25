<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-medium text-[#171717] mb-2">My Orders</h1>
        <p class="text-gray-600">Track and manage your recent purchases</p>
    </div>

    @if (session('message'))
        <div class="flex items-center bg-green-50 text-green-700 px-4 py-3 rounded-lg border border-green-200 mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif

    @if ($orders->count() > 0)
        <!-- Orders List -->
        <div class="space-y-6">
            @foreach ($orders as $order)
                <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6">
                    <!-- Order Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-gray-100 pb-4 mb-4">
                        <div class="mb-3 md:mb-0">
                            <h2 class="text-lg font-medium text-[#171717]">Order #{{ $order->order_number }}</h2>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Placed on {{ $order->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="flex flex-col md:items-end">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                   ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'canceled' ? 'bg-red-100 text-red-800' : 
                                   ($order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : 
                                   ($order->status === 'delivered' ? 'bg-teal-100 text-teal-800' : 
                                   ($order->status === 'returned' ? 'bg-orange-100 text-orange-800' : 
                                   'bg-gray-100 text-gray-800')))))) }}">
                                <i class="fas 
                                    {{ $order->status === 'pending' ? 'fa-clock' : 
                                       ($order->status === 'processing' ? 'fa-cog' : 
                                       ($order->status === 'completed' ? 'fa-check' : 
                                       ($order->status === 'canceled' ? 'fa-times' : 
                                       ($order->status === 'shipped' ? 'fa-shipping-fast' : 
                                       ($order->status === 'delivered' ? 'fa-box-open' : 
                                       ($order->status === 'returned' ? 'fa-undo' : 
                                       'fa-info-circle')))))) }} mr-1 text-xs">
                                </i>
                                {{ ucfirst($order->status) }}
                            </span>
                            <p class="text-xl font-medium text-[#171717] mt-2">₹{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="space-y-4">
                        @foreach ($order->orderItems as $item)
                            <div class="flex flex-col sm:flex-row gap-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if ($item->product && $item->product->firstVariantImage->first())
                                        <img src="{{ $item->product->firstVariantImage->image_path }}"
                                            alt="{{ $item->product->name }}" 
                                            class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-sm"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    @if ($item->product)
                                        <a href="{{ route('view.product', $item->product->slug) }}" 
                                           class="font-medium text-[#171717] hover:text-[#8f4da7] transition-colors line-clamp-1">
                                            {{ $item->product->name }}
                                        </a>

                                        @if ($item->product->brand)
                                            <p class="text-sm text-gray-600 mt-1">Brand: {{ $item->product->brand->name }}</p>
                                        @endif

                                        <!-- Price and Quantity -->
                                        <div class="flex flex-wrap items-center gap-3 mt-2">
                                            <span class="text-base font-medium text-green-600">
                                                ₹{{ number_format($item->product->discount_price, 2) }}
                                            </span>
                                            <span class="text-sm text-gray-400 line-through">
                                                ₹{{ number_format($item->product->price, 2) }}
                                            </span>
                                            <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                        </div>

                                        <!-- Customization Section -->
                                        @if ($item->product->is_customizable)
                                            <div class="mt-3 p-2 bg-purple-50 rounded border border-purple-100">
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-palette text-[#8f4da7] mr-2 text-sm"></i>
                                                        <span class="text-sm font-medium text-[#8f4da7]">Customizable Product</span>
                                                    </div>
                                                    <a href="{{ $this->getCustomizationWhatsappUrl($item->product->name, $order->order_number) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded transition-colors">
                                                        <i class="fab fa-whatsapp mr-1"></i>
                                                        Customize
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Product Highlights -->
                                        @if ($item->product->highlights->count() > 0)
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-500 mb-1">Key Features:</p>
                                                <ul class="text-xs text-gray-600 space-y-1">
                                                    @foreach ($item->product->highlights->take(2) as $highlight)
                                                        <li class="flex items-center">
                                                            <i class="fas fa-check text-green-500 mr-1 text-xs"></i>
                                                            {{ $highlight->highlights }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                            <a href="{{ route('public.product.view', $item->product->slug) }}"
                                               class="inline-flex items-center px-3 py-2 bg-[#8f4da7] hover:bg-[#7a3d8f] text-white text-xs rounded-lg transition-colors">
                                                <i class="fas fa-eye mr-1 text-xs"></i>
                                                View Product
                                            </a>
                                            @if ($order->status === 'delivered')
                                                <div class="flex items-center text-[#8f4da7] hover:text-[#7a3d8f] transition-colors">
                                                    <i class="fas fa-star mr-1 text-sm"></i>
                                                    <a href="{{ route('review', $item->product->slug) }}" 
                                                       class="text-sm hover:underline">
                                                        Rate & Review
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <h3 class="font-medium text-[#171717]">Product Not Available</h3>
                                        <p class="text-sm text-gray-600 mt-1">This product may have been removed or is no longer available.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-credit-card mr-2 text-[#8f4da7]"></i>
                                    <span class="font-medium mr-1">Payment:</span> {{ $order->payment_method }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-2 text-[#8f4da7]"></i>
                                    <span class="font-medium mr-1">Status:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($order->payment_status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                @if ($order->address)
                                    <div class="text-sm text-gray-600">
                                        <div class="flex items-start">
                                            <i class="fas fa-map-marker-alt mr-2 text-[#8f4da7] mt-0.5"></i>
                                            <div>
                                                <span class="font-medium">Delivery Address:</span>
                                                <p class="text-xs mt-1">{{ $order->address->name }}, {{ $order->address->address_line }},
                                                    {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->postal_code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-medium text-[#171717]">₹{{ number_format($order->total_amount, 2) }}</p>
                                @if ($order->status === 'pending' || $order->status === 'processing')
                                    <p class="text-sm text-green-600 mt-1 flex items-center justify-end">
                                        <i class="fas fa-truck mr-1"></i>
                                        Expected delivery in 3-5 days
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Support Section -->
                    <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center">
                                <i class="fab fa-whatsapp text-green-600 mr-2 text-lg"></i>
                                <span class="text-sm font-medium text-green-800">Need help with this order?</span>
                            </div>
                            <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $whatsappNumber) }}?text={{ urlencode('Hi! I need help with my order: ' . $order->order_number) }}"
                               target="_blank"
                               class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors">
                                <i class="fas fa-headset mr-1"></i>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <i class="fas fa-shopping-bag text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-[#171717] mb-2">No orders yet</h3>
            <p class="text-gray-600 mb-6">You haven't placed any orders yet. Start shopping to see your orders here.</p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center px-6 py-3 bg-[#8f4da7] hover:bg-[#7a3d8f] text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-shopping-cart mr-2"></i>
                Start Shopping
            </a>
        </div>
    @endif

    <style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .transition-colors {
        transition-property: color, background-color, border-color;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }

    /* Custom color classes */
    .text-\[\#171717\] {
        color: #171717;
    }

    .text-\[\#8f4da7\] {
        color: #8f4da7;
    }

    .bg-\[\#8f4da7\] {
        background-color: #8f4da7;
    }

    .hover\:bg-\[\#7a3d8f\]:hover {
        background-color: #7a3d8f;
    }

    .hover\:text-\[\#8f4da7\]:hover {
        color: #8f4da7;
    }
</style>
</div>

