<div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-12 py-8 mt-[70px]">
    <!-- Cart Section -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Your Cart</h1>

        @if ($order && $order->orderItems->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                <!-- Cart Items -->
                @foreach ($order->orderItems as $item)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b pb-4 mb-4">
                        <!-- Product Image and Details -->
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('image/product/' . $item->product->images->first()->path) }}"
                                alt="{{ e($item->product->name) }}"
                                class="w-24 h-24 object-contain rounded-md">
                            <div>
                                <h2 class="text-lg font-medium text-gray-800">{{ e($item->product->name) }}</h2>
                                <p class="text-gray-600">₹{{ $item->product->price }}</p>
                                @if ($item->size_variant_id)
                                    <p class="text-sm text-gray-500">Size: {{ $item->sizeVariant->variant_name }}</p>
                                @endif
                                @if ($item->color_variant_id)
                                    <p class="text-sm text-gray-500">Color: {{ $item->colorVariant->variant_name }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Quantity and Actions -->
                        <div class="flex items-center gap-4 mt-4 sm:mt-0">
                            <div class="flex items-center">
                                <button
                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                    class="px-2 py-1 bg-gray-200 text-gray-800 rounded-l-md hover:bg-gray-300"
                                    aria-label="Decrease quantity"
                                    @if ($item->quantity <= 1) disabled @endif>
                                    -
                                </button>
                                <input
                                    type="number"
                                    wire:model="orderItems.{{ $item->id }}.quantity"
                                    class="w-16 px-3 py-1 border border-gray-300 text-center"
                                    min="1"
                                    aria-label="Quantity"
                                    readonly>
                                <button
                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                    class="px-2 py-1 bg-gray-200 text-gray-800 rounded-r-md hover:bg-gray-300"
                                    aria-label="Increase quantity">
                                    +
                                </button>
                            </div>
                            <button
                                wire:click="removeItem({{ $item->id }})"
                                class="text-red-600 hover:text-red-800"
                                aria-label="Remove {{ e($item->product->name) }} from cart">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Cart Summary -->
                <div class="mt-6 flex justify-between items-center">
                    <div>
                        <p class="text-lg font-medium text-gray-800">Total Items: {{ $order->orderItems->count() }}</p>
                        <p class="text-xl font-bold text-gray-800">Total: ₹{{ $this->getTotal() }}</p>
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('public.home') }}"
                           class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-200">
                            Continue Shopping
                        </a>
                        <a href="{{ route('public.home') }}"
                           class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-200">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-10">
                <p class="text-lg text-gray-600">Your cart is empty.</p>
                <a href="{{ route('home') }}"
                   class="mt-4 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-200">
                    Shop Now
                </a>
            </div>
        @endif

        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mt-4 p-4 bg-red-100 text-red-800 rounded-md">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>