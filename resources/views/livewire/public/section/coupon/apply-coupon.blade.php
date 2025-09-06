<div>
    <div class="mt-2">
        <h2 class="py-2 text-gray-900">Apply Coupon</h2>
        @if ($couponError)
            <div class="bg-red-50 border-l-4 border-red-400 text-red-600 p-2 mb-2 rounded" role="alert">
                {{ $couponError }}
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 text-green-600 p-2 mb-2 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <!-- NEW: Display list of available coupons -->
        <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-700">Available Coupons</h3>
            @if ($coupons->isEmpty())
                <p class="text-gray-500">No coupons available.</p>
            @else
                <ul class="space-y-2">
                    @foreach ($coupons as $coupon)
                        <li class="flex justify-between items-center p-2 border rounded">
                            <div>
                                <span class="font-bold">{{ $coupon->code }}</span>
                                <span class="text-sm text-gray-600">
                                    @if ($coupon->discount_type === 'percentage')
                                        {{ $coupon->discount_value }}% off
                                    @elseif ($coupon->discount_type === 'fixed')
                                        ₹{{ number_format($coupon->discount_value, 2) }} off
                                    @else
                                        Free Shipping
                                    @endif
                                    @if ($coupon->minimum_purchase_amount)
                                        (Min. purchase: ₹{{ number_format($coupon->minimum_purchase_amount, 2) }})
                                    @endif
                                </span>
                            </div>
                            <button 
                                wire:click="applyCoupon('{{ $coupon->code }}')"
                                @if ($cartTotal < $coupon->minimum_purchase_amount || ($appliedCoupon && $appliedCoupon !== $coupon->code))
                                    disabled
                                    class="px-2 py-1 bg-gray-300 text-gray-500 rounded cursor-not-allowed"
                                @else
                                    class="px-2 py-1 bg-primary text-white rounded hover:bg-primary-dark transition duration-200"
                                @endif
                            >
                                Apply
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <!-- CHANGED: Added disabled attribute for input/button when a coupon is applied -->
        {{-- <div class="flex gap-2">
            <input 
                type="text" 
                wire:model.live="couponCode" 
                placeholder="Enter coupon code" 
                class="px-2 py-1 border rounded border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary"
                @if ($appliedCoupon) disabled @endif
            >
            <button 
                wire:click="applyCoupon" 
                class="px-2 py-1 bg-primary text-white rounded hover:bg-primary-dark transition duration-200"
                @if ($appliedCoupon) disabled @endif
            >
                Apply Coupon
            </button>
        </div> --}}
        @error('couponCode')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
        <!-- NEW: Button to remove applied coupon -->
        @if ($appliedCoupon)
            <div class="mt-2">
                <button 
                    wire:click="removeCoupon"
                    class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-200"
                >
                    Remove Coupon
                </button>
            </div>
        @endif
    </div>
</div>