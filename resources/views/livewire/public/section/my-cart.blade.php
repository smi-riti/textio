<div>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <h1 class="text-2xl sm:text-3xl font-semibold text-[#171717] mb-6">My Cart</h1>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Column - Cart Items -->
            <div class="w-full lg:w-8/12">
                <div class="bg-white rounded-lg p-4 sm:p-4">
                    @forelse ($cartItems as $item)
                        @php
                            $itemPrice = $this->getItemPrice($item);
                            $itemRegularPrice = $this->getItemRegularPrice($item);
                            $discountPercentage = $this->getItemDiscountPercentage($item);
                            $hasDiscount = $discountPercentage > 0;
                        @endphp

                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6  pb-4 border-b last:border-b-0">
                            <!-- Product Image - Mobile Optimized -->
                            <a href="{{ route('view.product', $item->product->slug) }}" class="sm:flex-shrink-0">
                                <div
                                    class="w-full sm:w-32 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden mx-auto sm:mx-0">
                                    @if ($item->product->images && $item->product->images->first())
                                        <img src="{{ $item->product->images->first()->image_path }}"
                                            alt="{{ $item->product->name }}" class="w-full h-auto object-contain">
                                    @else
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    @endif
                                </div>

                            </a>

                            <!-- Product Details - Mobile Optimized -->
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div class="w-full">
                                        <h2 class="text-lg font-semibold text-[#171717] mb-2 line-clamp-2">
                                            {{ $item->product->name ?? 'Unknown Product' }}
                                        </h2>

                                        <!-- Variant Details -->
                                        @php
                                            $variants = [];
                                            if (
                                                $item->variantCombination &&
                                                $item->variantCombination->variant_values
                                            ) {
                                                $variants = is_string($item->variantCombination->variant_values)
                                                    ? json_decode($item->variantCombination->variant_values, true)
                                                    : $item->variantCombination->variant_values;
                                            }
                                        @endphp

                                        @if (!empty($variants))
                                            <div class="text-gray-600 text-sm mb-2 flex flex-wrap gap-1">
                                                @foreach ($variants as $type => $value)
                                                    <span class="bg-gray-100 px-2 py-1 rounded-md text-xs capitalize">
                                                        {{ $type }}: {{ $value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-600 text-sm mb-2">Free Size</p>
                                        @endif

                                        <p class="text-gray-500 text-xs mb-3">Seller: RetailNet</p>

                                        <!-- Price Section - Mobile Optimized -->
                                        <div class="flex items-center gap-3 mb-4">
                                            <span class="text-xl font-bold text-[#171717]">
                                                ₹{{ number_format($itemPrice * $item->quantity, 0) }}
                                            </span>
                                            @if ($hasDiscount)
                                                <del class="text-sm text-gray-500">
                                                    ₹{{ number_format($itemRegularPrice * $item->quantity, 0) }}
                                                </del>
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                                                    {{ $discountPercentage }}% OFF
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Controls - Mobile Optimized -->
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center bg-gray-100 rounded-lg p-1">
                                        <button wire:click="decreaseQuantity({{ $item->id }})"
                                            class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-[#8f4da7] transition rounded-lg">
                                            <i class="fas fa-minus text-sm"></i>
                                        </button>
                                        <span class="mx-3 text-gray-800 font-medium text-sm min-w-[20px] text-center">
                                            {{ $item->quantity }}
                                        </span>
                                        <button wire:click="increaseQuantity({{ $item->id }})"
                                            class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-[#8f4da7] transition rounded-lg">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                    </div>
                                    <button wire:click="removeItem({{ $item->id }})"
                                        class="bg-red-50 text-red-600 hover:bg-red-100 w-10 h-10 flex items-center justify-center rounded-lg transition">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div
                                class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-600 text-lg mb-4">Your cart is empty</p>
                            <a href="{{ route('public.product.all') }}"
                                class="inline-block bg-[#8f4da7] hover:bg-[#7a3c93] text-white py-3 px-8 rounded-lg transition font-medium">
                                Continue Shopping
                            </a>
                        </div>
                    @endforelse
                </div>

                @if ($cartItems->isNotEmpty())
                    <div class="bg-white hidden sm:flex rounded-lg justify-end p-2 mb-4">
                        <button wire:click="placeOrder"
                            class="bg-[#8f4da7] hover:bg-[#7a3c93] text-white py-2 px-16 rounded flex items-center justify-center transition">
                            Place Order
                        </button>
                    </div>
                @endif
            </div>

            <!-- Right Column - Order Summary -->
            <div class="w-full lg:w-4/12 md:mb-0 mb-32">
                <div class="bg-white rounded-lg p-5 sm:p-6 sticky top-10 shadow-sm">
                    <h2 class="text-lg sm:text-xl font-semibold text-[#171717] mb-4 border-b pb-3">Order Summary</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Price ({{ $cartItems->count() }} items)</span>
                            <span
                                class="text-gray-800">₹{{ number_format($cartItems->sum(fn($item) => $this->getItemPrice($item) * $item->quantity), 0) }}</span>
                        </div>

                        @php
                            $totalDiscount = $cartItems->sum(function ($item) {
                                $price = $this->getItemPrice($item);
                                $regularPrice = $this->getItemRegularPrice($item);
                                return ($regularPrice - $price) * $item->quantity;
                            });
                        @endphp

                        @if ($totalDiscount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount</span>
                                <span class="text-green-600">-₹{{ number_format($totalDiscount, 0) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Delivery</span>
                            <span class="text-green-600">FREE</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-[#171717]">Total Amount</span>
                            <span class="text-xl font-bold text-[#8f4da7]">
                                ₹{{ number_format($cartItems->sum(fn($item) => $this->getItemPrice($item) * $item->quantity), 0) }}
                            </span>
                        </div>

                        @if ($totalDiscount > 0)
                            <p class="text-green-600 mt-2 text-sm bg-green-50 p-2 rounded-lg">
                                🎉 You saved ₹{{ number_format($totalDiscount, 0) }} on this order!
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Place Order Button - Mobile Optimized -->
        @if ($cartItems->isNotEmpty())
            <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white shadow-2xl p-4 z-50 border-t border-gray-200">
                <div class="container mb-20 mx-auto flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Total Amount</p>
                        <p class="text-lg font-bold text-[#8f4da7]">
                            ₹{{ number_format($cartItems->sum(fn($item) => $this->getItemPrice($item) * $item->quantity), 0) }}
                        </p>
                    </div>
                    <button wire:click="placeOrder"
                        class="bg-[#8f4da7] hover:bg-[#7a3c93] text-white py-3 px-8 rounded-xl font-semibold text-base transition shadow-lg flex items-center gap-2">
                        <i class="fas fa-shopping-bag"></i>
                        Place Order
                    </button>
                </div>
            </div>
        @endif
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 640px) {
            /* .container {
            padding-bottom: 100px;
        } */
        }
    </style>
</div>
