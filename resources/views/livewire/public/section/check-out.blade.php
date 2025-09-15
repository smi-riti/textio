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
                                @if (!empty($item['product']['image']))
                                    <img src="{{ $item['product']['image'] }}"
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
                                @if (!empty($item['variant_details']) && (is_array($item['variant_details']) || is_object($item['variant_details'])))
                                    @php
                                        $variants = is_string($item['variant_details'])
                                            ? json_decode($item['variant_details'], true)
                                            : $item['variant_details'];
                                    @endphp
                                    @foreach ($variants as $type => $value)
                                        {{ $type }}: {{ is_array($value) ? implode(', ', $value) : $value }}
                                        @if (!$loop->last)
                                            |
                                        @endif
                                    @endforeach
                                @else
                                    Free Size
                                @endif
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

  <!-- Existing HTML structure remains the same -->

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('livewire:initialized', function () {
        let isPaymentInProgress = false;
        
        Livewire.on('init-razorpay', (data) => {
            console.log('=== RAZORPAY INIT DEBUG ===');
            console.log('Raw data received:', data);
            console.log('Data type:', typeof data);
            console.log('Is array:', Array.isArray(data));
            
            // Handle both array and object formats from Livewire
            const options = Array.isArray(data) ? data[0] : data;
            console.log('Processed options:', options);
            console.log('Amount:', options?.amount, 'Type:', typeof options?.amount);
            
            // Prevent multiple payment attempts
            if (isPaymentInProgress) {
                console.log('Payment already in progress, ignoring...');
                return;
            }
            
            // Validate required options
            if (!options || typeof options !== 'object') {
                console.error('Invalid options received:', options);
                alert('Invalid payment configuration. Please try again.');
                resetPaymentState();
                return;
            }
            
            // Convert amount to number if it's a string
            let amount = options.amount;
            if (typeof amount === 'string') {
                amount = parseInt(amount, 10);
                console.log('Converted string amount to number:', amount);
            }
            
            if (!amount || isNaN(amount) || amount <= 0) {
                console.error('Invalid amount:', amount, 'Original:', options.amount);
                alert('Invalid payment amount. Please refresh and try again.');
                resetPaymentState();
                return;
            }
            
            // Ensure amount is in paise (should be >= 100 paise = 1 rupee minimum)
            if (amount < 100) {
                console.error('Amount too small (minimum 1 rupee = 100 paise):', amount);
                alert('Invalid payment amount. Please refresh and try again.');
                resetPaymentState();
                return;
            }
            
            // Validate maximum amount (10 lakhs = 1,00,00,000 paise)
            if (amount > 10000000) {
                console.error('Amount too large:', amount);
                alert('Payment amount exceeds maximum limit. Please contact support.');
                resetPaymentState();
                return;
            }
            
            console.log('Amount validation passed:', amount);
            
            if (!options.razorpay_order_id || typeof options.razorpay_order_id !== 'string') {
                console.error('Invalid order ID:', options.razorpay_order_id);
                alert('Invalid order ID. Please refresh and try again.');
                resetPaymentState();
                return;
            }
            
            // Validate Razorpay key
            const razorpayKey = '{{ config('services.razorpay.key', env('RAZORPAY_KEY')) }}';
            if (!razorpayKey || razorpayKey.trim() === '') {
                alert('Payment gateway not configured. Please contact support.');
                resetPaymentState();
                return;
            }
            
            isPaymentInProgress = true;
            
            // Show loading state
            const confirmButton = document.querySelector('button[wire\\:click="confirmOrder"]');
            if (confirmButton) {
                confirmButton.disabled = true;
                confirmButton.textContent = 'Processing...';
            }

            const rzpOptions = {
                key: razorpayKey,
                amount: amount, // Use the validated amount variable
                currency: 'INR',
                order_id: options.razorpay_order_id,
                name: options.name || '{{ config('app.name', 'Your Company') }}',
                description: options.description || 'Order Payment',
                image: '{{ asset('favicon.ico') }}', // Add your logo here
                prefill: {
                    name: options.prefill?.name || '',
                    email: options.prefill?.email || '',
                    contact: options.prefill?.contact || ''
                },
                theme: {
                    color: '#3B82F6' // Customize theme color
                },
                handler: function (response) {
                    console.log('Payment successful:', response);
                    
                    // Validate response
                    if (!response.razorpay_payment_id || !response.razorpay_order_id || !response.razorpay_signature) {
                        alert('Incomplete payment response. Please contact support with Order #' + (options.order_number || ''));
                        resetPaymentState();
                        return;
                    }
                    
                    // Show success message temporarily
                    if (confirmButton) {
                        confirmButton.textContent = 'Verifying Payment...';
                    }
                    
                    // Dispatch verification event
                    Livewire.dispatch('verify-payment', {
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature
                    });
                },
                modal: {
                    ondismiss: function () {
                        console.log('Payment cancelled by user');
                        Livewire.dispatch('payment-failed', {
                            razorpay_order_id: options.razorpay_order_id,
                            error: 'Payment cancelled by user'
                        });
                        resetPaymentState();
                    },
                    escape: true,
                    backdropclose: false
                },
                retry: {
                    enabled: true,
                    max_count: 3
                },
                timeout: 300, // 5 minutes timeout
                remember_customer: true
            };

            try {
                const rzp = new Razorpay(rzpOptions);
                
                // Handle payment failures
                rzp.on('payment.failed', function (response) {
                    console.error('Payment failed:', response.error);
                    
                    let errorMessage = 'Payment failed. ';
                    let userFriendlyMessage = 'Payment failed. Please try again.';
                    
                    if (response.error && response.error.code) {
                        const errorCode = response.error.code;
                        const errorDesc = response.error.description || '';
                        
                        // Map common error codes to user-friendly messages
                        switch (errorCode) {
                            case 'BAD_REQUEST_ERROR':
                                userFriendlyMessage = 'Invalid payment details. Please check your information and try again.';
                                break;
                            case 'GATEWAY_ERROR':
                                userFriendlyMessage = 'Payment gateway error. Please try again or use a different payment method.';
                                break;
                            case 'NETWORK_ERROR':
                                userFriendlyMessage = 'Network error. Please check your internet connection and try again.';
                                break;
                            case 'SERVER_ERROR':
                                userFriendlyMessage = 'Server error. Please try again in a moment.';
                                break;
                            default:
                                userFriendlyMessage = errorDesc || 'Payment failed. Please try again.';
                        }
                        
                        errorMessage += 'Code: ' + errorCode;
                        if (errorDesc) {
                            errorMessage += ' - ' + errorDesc;
                        }
                    }
                    
                    alert(userFriendlyMessage);
                    
                    Livewire.dispatch('payment-failed', {
                        razorpay_order_id: options.razorpay_order_id,
                        error: errorMessage,
                        error_code: response.error?.code || 'UNKNOWN_ERROR',
                        error_description: response.error?.description || 'Unknown payment error'
                    });
                    
                    resetPaymentState();
                });
                
                // Open payment modal
                rzp.open();
                
            } catch (error) {
                console.error('Error initializing Razorpay:', error);
                alert('Failed to initialize payment gateway. Please try again.');
                resetPaymentState();
            }
        });
        
        // Reset payment state function
        function resetPaymentState() {
            isPaymentInProgress = false;
            const confirmButton = document.querySelector('button[wire\\:click="confirmOrder"]');
            if (confirmButton) {
                confirmButton.disabled = false;
                confirmButton.textContent = 'Confirm Order';
            }
        }
        
        // Listen for successful verification to reset state
        Livewire.on('payment-verified', () => {
            console.log('Payment verified successfully');
            if (confirmButton) {
                confirmButton.textContent = 'Payment Successful!';
            }
        });
        
        // Handle network errors
        window.addEventListener('online', function() {
            if (isPaymentInProgress) {
                console.log('Network connection restored');
            }
        });
        
        window.addEventListener('offline', function() {
            if (isPaymentInProgress) {
                alert('Network connection lost. Please check your internet connection.');
            }
        });
    });
</script>
</div>
</div>