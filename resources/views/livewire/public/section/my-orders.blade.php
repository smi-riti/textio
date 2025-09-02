<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">My Orders</h1>

    @if (session('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Order Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b pb-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Order #{{ $order->order_number }}</h2>
                        <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="mt-3 md:mt-0 flex flex-col md:items-end">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                   ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'canceled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <p class="text-lg font-bold text-gray-900 mt-1">₹{{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                            @if($item->product && $item->product->images->first())
                                <img src="{{ $item->product->images->first()->image_path }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-20 h-20 object-cover rounded">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            @endif

                            <div class="flex-1">
                                @if($item->product)
                                    <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                    
                                    @if($item->product->brand)
                                        <p class="text-sm text-gray-600">Brand: {{ $item->product->brand->name }}</p>
                                    @endif

                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="text-lg font-bold text-green-600">
                                            ₹{{ number_format($item->product->discount_price, 2) }}
                                        </span>
                                        <span class="text-sm text-gray-400 line-through">₹{{ number_format($item->product->price, 2) }}</span>


                                        <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                    </div>

                                    <!-- Customization Section for Products -->
                                    @if($item->product->is_customizable)
                                        <div class="mt-2 p-2 bg-gradient-to-r from-purple-50 to-blue-50 rounded border border-purple-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-purple-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-purple-700">Customizable Product</span>
                                                </div>
                                                <a href="{{ $this->getCustomizationWhatsappUrl($item->product->name, $order->order_number) }}" 
                                                   target="_blank" 
                                                   class="inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                    </svg>
                                                    Customize
                                                </a>
                                            </div>
                                            
                                        </div>
                                    @endif

                                    <!-- Product Highlights -->
                                    @if($item->product->highlights->count() > 0)
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-500 mb-1">Key Features:</p>
                                            <ul class="text-xs text-gray-600">
                                                @foreach($item->product->highlights->take(2) as $highlight)
                                                    <li class="flex items-center">
                                                        <svg class="w-2 h-2 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        {{ $highlight->highlights }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <!-- View Product Button -->
                                    <div class="mt-2">
                                        <a href="{{ route('public.product.view', $item->product->slug) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs rounded-lg transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Product
                                        </a>
                                    </div>

                                  
                                @else
                                    <h3 class="font-semibold text-gray-900">Product Not Available</h3>
                                    <p class="text-sm text-gray-600">This product may have been removed or is no longer available.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Footer -->
                <div class="mt-6 pt-4 border-t">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="text-sm text-gray-600">
                            <p><span class="font-medium">Payment:</span> {{ $order->payment_method }}</p>
                            <p><span class="font-medium">Status:</span> 
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($order->payment_status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                            @if($order->address)
                                <p class="mt-2"><span class="font-medium">Delivery Address:</span></p>
                                <p class="text-xs">{{ $order->address->name }}, {{ $order->address->address_line }}, {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->postal_code }}</p>
                            @endif
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($order->total_amount, 2) }}</p>
                            @if($order->status === 'pending' || $order->status === 'processing')
                                <p class="text-sm text-green-600 mt-1">Expected delivery in 3-5 business days</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Support -->
                <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span class="text-sm font-medium text-green-800">Need help with this order?</span>
                        </div>
                        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $whatsappNumber) }}?text={{ urlencode('Hi! I need help with my order: ' . $order->order_number) }}" 
                           target="_blank" 
                           class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8h.01M4 11h.01"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No orders yet</h3>
            <p class="mt-1 text-sm text-gray-500">You haven't placed any orders yet. Start shopping to see your orders here.</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
   
</div>


