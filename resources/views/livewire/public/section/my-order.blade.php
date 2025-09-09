<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-2xl font-normal text-gray-900 mb-8">Confirm Your Order</h1>

    <!-- Error and Success Messages -->
    @foreach (['error' => 'red', 'success' => 'green', 'message' => 'green'] as $type => $color)
        @if (session($type))
            <div class="bg-{{ $color }}-50 border-l-4 border-{{ $color }}-400 text-{{ $color }}-600 p-4 mb-6 rounded"
                role="alert">
                <p>{{ session($type) }}</p>
            </div>
        @endif
    @endforeach

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Section -->
        <div class="w-full lg:w-8/12 space-y-6">
            <!-- Login Section -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full">1</span>
                    <p class="text-lg text-gray-900">Login</p>
                </div>
                <p class="text-gray-600 ml-11 break-all">{{ $userEmail }}</p>
            </div>

            <!-- Delivery Address Section -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <span
                        class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full mr-3">2</span>
                    <h1 class="text-lg text-gray-900">Delivery Address</h1>
                </div>

                <div class="ml-11 border-l-2 border-primary pl-4 py-2" x-data="{ showAll: false }">
                    @foreach ($addresses->take(3) as $address)
                        <div class="flex flex-col sm:flex-row justify-between gap-2 p-4 mb-3 border rounded-lg">
                            <label class="flex items-start cursor-pointer flex-1">
                                <input type="radio" wire:model="addressId" value="{{ $address->id }}"
                                    class="h-5 w-5 text-primary mt-1" required>
                                <div class="ml-3 text-sm sm:text-base">
                                    <p class="text-gray-900">{{ $address->name }}</p>
                                    <p class="text-gray-600">{{ $address->address_type ?? 'Home' }}</p>
                                    <p class="text-gray-600">{{ $address->phone }}</p>
                                    <p class="text-gray-600 mt-2 leading-snug">
                                        {{ $address->address_line }}, {{ $address->city }}, {{ $address->state }} -
                                        {{ $address->postal_code }}
                                    </p>
                                </div>
                            </label>
                            <button wire:click="$dispatch('open-edit', { id: {{ $address->id }} })"
                                class="text-purple-600 hover:text-purple-800 transition-colors duration-200">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                        </div>
                    @endforeach

                    @if ($addresses->count() > 3)
                        <div x-show="showAll" class="space-y-3">
                            @foreach ($addresses->slice(3) as $address)
                                <div class="flex flex-col sm:flex-row justify-between gap-2 p-4 border rounded-lg">
                                    <label class="flex items-start cursor-pointer flex-1">
                                        <input type="radio" wire:model="addressId" value="{{ $address->id }}"
                                            class="h-5 w-5 text-primary mt-1" required>
                                        <div class="ml-3 text-sm sm:text-base">
                                            <p class="text-gray-900">{{ $address->name }}</p>
                                            <p class="text-gray-600">{{ $address->address_type ?? 'Home' }}</p>
                                            <p class="text-gray-600">{{ $address->phone }}</p>
                                            <p class="text-gray-600 mt-2 leading-snug">
                                                {{ $address->address_line }}, {{ $address->city }},
                                                {{ $address->state }} - {{ $address->postal_code }}
                                            </p>
                                        </div>
                                    </label>
                                    <button wire:click="$dispatch('open-edit', { id: {{ $address->id }} })"
                                        class="text-purple-600 hover:text-purple-800 transition-colors duration-200">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button @click="showAll = !showAll"
                            class="mt-4 w-full sm:w-auto bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition duration-200">
                            <span x-text="showAll ? 'Show Less' : 'View More'"></span>
                        </button>
                    @endif

                    <div class="flex justify-end items-center mt-4">
                        <button wire:click="$dispatch('open-add')"
                            class="border border-purple-200 text-purple-600 text-sm hover:text-white px-4 py-2 rounded hover:bg-purple-700 transition-colors duration-200">
                            <i class="fas fa-plus text-sm"></i> Add New Address
                        </button>
                    </div>

                    <livewire:public.section.accounts.address-upadate />
                </div>
            </div>


            <!-- Order Summary Section -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <span
                        class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full mr-3">3</span>
                    <h1 class="text-lg text-gray-900">Order Summary</h1>
                </div>

                @foreach ($cartItems as $item)
                    <div class="ml-11 mt-4 border border-gray-200 rounded-lg">
                        <div class="flex flex-col sm:flex-row p-4">
                            <div class="sm:w-24 h-32 bg-gray-100 flex items-center justify-center mb-4 sm:mb-0 rounded">
                                @if ($item['product']['image'])
                                    <img src="{{  $item['product']['image'] }}"
                                        alt="{{ $item['product']['name'] }}"
                                        class="w-full h-full object-cover rounded">
                                @else
                                    <span class="text-gray-500 text-sm">Product Image</span>
                                @endif
                            </div>
                            <div class="sm:ml-4 flex-1 text-sm sm:text-base">
                                <h3 class="text-lg text-gray-900">
                                    {{ $item['product']['name'] ?? 'Unknown Product' }}
                                </h3>
                                @if (!empty($item['variant_details']))
                                    @php
                                        $variants = is_string($item['variant_details'])
                                            ? json_decode($item['variant_details'], true)
                                            : $item['variant_details'];
                                    @endphp

                                    @foreach ($variants as $type => $value)
                                        {{ $type }}: {{ $value }}
                                        @if (!$loop->last)
                                            |
                                        @endif
                                    @endforeach
                                @else
                                    Free Size
                                @endif


                                <!-- FIXED PRICE DISPLAY SECTION -->
                                <div class="flex items-center mt-2 flex-wrap gap-2">
                                    @php
                                        $regularPrice = $item['product']['price'] ?? 0;
                                        $discountPrice = $item['product']['discount_price'] ?? $regularPrice;
                                        $hasDiscount = $regularPrice > $discountPrice;
                                    @endphp

                                    <span class="text-gray-900">
                                        ₹{{ number_format($discountPrice * $item['quantity'], 2) }}
                                    </span>

                                    @if ($hasDiscount)
                                        <span class="text-gray-500 line-through">
                                            ₹{{ number_format($regularPrice * $item['quantity'], 2) }}
                                        </span>
                                        @php
                                            $savingPercentage =
                                                $regularPrice > 0
                                                    ? round((($regularPrice - $discountPrice) / $regularPrice) * 100)
                                                    : 0;
                                        @endphp
                                        <span class="text-green-600">
                                            {{ $savingPercentage }}% Off
                                        </span>
                                    @endif
                                </div>
                                <!-- END OF FIXED PRICE DISPLAY SECTION -->

                                <p class="mt-2 text-gray-600">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Payment Section -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <span
                        class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full mr-3">4</span>
                    <h1 class="text-lg text-gray-900">Payment Method</h1>
                </div>
                <div class="ml-11 mt-4 space-y-3">
                    @foreach (['Cash on Delivery', 'UPI'] as $method)
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model="paymentMethod" value="{{ $method }}"
                                class="h-5 w-5 text-primary" required>
                            <span class="ml-3 text-gray-700">{{ $method }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Order Confirmation -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <p class="text-gray-600 mb-4">Order confirmation email will be sent to {{ $userEmail }}</p>
                <button wire:click="confirmOrder"
                    class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-4 rounded-lg transition duration-200 {{ !$addressId || !$paymentMethod ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ !$addressId || !$paymentMethod ? 'disabled' : '' }}>
                    Confirm Order
                </button>
            </div>
        </div>

        <!-- Right Section - Price Summary -->
        <!-- Right Section - Price Summary -->
        <div class="w-full lg:w-4/12">
            <div class="sticky top-6 bg-white rounded-lg p-6 border border-gray-200">
                <h2 class="text-lg text-gray-900 mb-4 border-b border-gray-200 pb-2">Price Details</h2>
                <div class="space-y-3 text-sm sm:text-base">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Price ({{ $cartItems->count() }}
                            item{{ $cartItems->count() > 1 ? 's' : '' }})</span>
                        <span class="text-gray-900">
                            ₹{{ number_format(
                                $cartItems->sum(function ($i) {
                                    $regularPrice = $i['product']['price'] ?? 0;
                                    $discountPrice = $i['product']['discount_price'] ?? $regularPrice;
                                    return $discountPrice * $i['quantity'];
                                }),
                                2,
                            ) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Product Discount</span>
                        <span>
                            ₹{{ number_format(
                                $cartItems->sum(function ($i) {
                                    $regularPrice = $i['product']['price'] ?? 0;
                                    $discountPrice = $i['product']['discount_price'] ?? $regularPrice;
                                    return ($regularPrice - $discountPrice) * $i['quantity'];
                                }),
                                2,
                            ) }}
                        </span>
                    </div>
                    @if ($discountAmount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Coupon Discount ({{ $couponCode }})</span>
                            <span>₹{{ number_format($discountAmount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-700">Delivery</span>
                        <span class="text-green-600">Free</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Secured Packaging Fee</span>
                        <span class="text-green-600">Free</span>
                    </div>
                </div>
                <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between text-lg text-gray-900">
                    <span>Total</span>
                    <span>₹{{ number_format($totalAmount, 2) }}</span>
                </div>
                <p class="text-green-600 mt-4 text-sm sm:text-base">
                    You will save
                    ₹{{ number_format(
                        $cartItems->sum(function ($i) {
                            $regularPrice = $i['product']['price'] ?? 0;
                            $discountPrice = $i['product']['discount_price'] ?? $regularPrice;
                            return ($regularPrice - $discountPrice) * $i['quantity'];
                        }) + $discountAmount,
                        2,
                    ) }}
                    on this order
                </p>

                <livewire:public.section.coupon.apply-coupon :cart-total="$cartItems->sum(function ($i) {
                    $regularPrice = $i['product']['price'] ?? 0;
                    $discountPrice = $i['product']['discount_price'] ?? $regularPrice;
                    return $discountPrice * $i['quantity'];
                })" />
            </div>
        </div>
    </div>
</div>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#8b5cf6',
                    'primary-dark': '#7c3aed',
                    'primary-light': '#a78bfa',
                }
            }
        }
    }
</script>
