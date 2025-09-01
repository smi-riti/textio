<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Confirm Your Order</h1>

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Section -->
        <div class="w-full lg:w-8/12 space-y-6">
            <!-- Login Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <span class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full">1</span>
                        <p class="text-lg font-medium">Login</p>
                    </div>
                </div>
                <p class="text-gray-600 ml-11">{{ $userEmail }}</p>
            </div>

            <!-- Delivery Address Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <span class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full mr-3">2</span>
                    <h1 class="text-lg font-bold">DELIVERY ADDRESS</h1>
                </div>

                @if ($address)
                    <!-- Display Address -->
                    <div class="ml-11 border-l-2 border-primary pl-4 py-2" x-data="{ isEditing: false }">
                        <div x-show="!isEditing">
                            <p class="font-medium">{{ $address->name }}</p>
                            <p class="text-gray-600">{{ $address->address_type ?? 'Home' }}</p>
                            <p class="text-gray-600">{{ $address->phone }}</p>
                            <p class="text-gray-600 mt-2">{{ $address->address_line }}, {{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}</p>
                            <button 
                                @click="isEditing = true" 
                                class="mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark"
                            >
                                Edit Address
                            </button>
                        </div>

                        <!-- Edit Address Form -->
                        <div x-show="isEditing" class="mt-4">
                            <form wire:submit="update({{ $address->id }})">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" wire:model="name" id="name" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="text" wire:model="phone" id="phone" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="address_type" class="block text-sm font-medium text-gray-700">Address Type</label>
                                        <input type="text" wire:model="address_type" id="address_type" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                        @error('address_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="address_line" class="block text-sm font-medium text-gray-700">Address Line</label>
                                        <input type="text" wire:model="address_line" id="address_line" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('address_line') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" wire:model="city" id="city" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                        <input type="text" wire:model="state" id="state" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('state') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                        <input type="text" wire:model="postal_code" id="postal_code" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('postal_code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-4">
                                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Update Address</button>
                                    <button type="button" @click="isEditing = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- No Address Available -->
                    <div class="ml-11 border-l-2 border-primary pl-4 py-2" x-data="{ isAdding: true }">
                        <div x-show="!isAdding">
                            <p class="text-red-600 font-medium">Please add a delivery address to proceed.</p>
                            <button 
                                @click="isAdding = true" 
                                class="mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark"
                            >
                                Add Address
                            </button>
                        </div>

                        <!-- Add Address Form -->
                        <div x-show="isAdding" class="mt-4">
                            <p class="text-red-600 font-medium mb-4">A delivery address is required to confirm your order.</p>
                            <form wire:submit="store">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" wire:model="name" id="name" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="text" wire:model="phone" id="phone" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="address_type" class="block text-sm font-medium text-gray-700">Address Type</label>
                                        <input type="text" wire:model="address_type" id="address_type" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                        @error('address_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="address_line" class="block text-sm font-medium text-gray-700">Address Line</label>
                                        <input type="text" wire:model="address_line" id="address_line" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('address_line') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" wire:model="city" id="city" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                        <input type="text" wire:model="state" id="state" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('state') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                        <input type="text" wire:model="postal_code" id="postal_code" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" required>
                                        @error('postal_code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-4">
                                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Save Address</button>
                                    <button type="button" @click="isAdding = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <span class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full mr-3">3</span>
                    <h1 class="text-lg font-bold">ORDER SUMMARY</h1>
                </div>
                @foreach($cartItems as $item)
                    <div class="ml-11 mt-4 border rounded-lg">
                        <div class="flex flex-col sm:flex-row p-4">
                            <div class="sm:w-24 h-32 bg-gray-200 flex items-center justify-center mb-4 sm:mb-0">
                                @if($item['product']['image'])
                                    <img src="{{ asset('storage/' . $item['product']['image']) }}" alt="{{ $item['product']['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-500">Product Image</span>
                                @endif
                            </div>
                            <div class="sm:ml-4 flex-1">
                                <h3 class="font-medium text-lg">{{ $item['product']['name'] ?? 'Unknown Product' }}</h3>
                                <p class="text-gray-600">
                                    @if($item['colorVariant'])
                                        Color: {{ $item['colorVariant']['variant_name'] }}
                                    @endif
                                    @if($item['sizeVariant'])
                                        {{ $item['colorVariant'] ? ' | ' : '' }}Size: {{ $item['sizeVariant']['variant_name'] }}
                                    @else
                                        Free Size
                                    @endif
                                </p>
                                <div class="flex items-center mt-2">
                                    <span class="text-lg font-bold">₹{{ number_format($item['product']['discount_price'] * $item['quantity'], 2) }}</span>
                                    @if($item['product']['price'] > $item['product']['discount_price'])
                                        <span class="text-gray-500 line-through ml-2">₹{{ number_format($item['product']['price'] * $item['quantity'], 2) }}</span>
                                        <span class="text-green-600 ml-3">
                                            @if($item['product']['price'] > 0)
                                                {{ round((($item['product']['price'] - $item['product']['discount_price']) / $item['product']['price']) * 100) }}% Off
                                            @else
                                                0% Off
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-2">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payment Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <span class="flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full mr-3">4</span>
                    <h1 class="text-lg font-bold">PAYMENT METHOD</h1>
                </div>
                <div class="ml-11 mt-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="payment" checked disabled class="h-5 w-5 text-primary">
                        <span class="ml-3 text-gray-700">Cash on Delivery</span>
                    </label>
                </div>
            </div>

            <!-- Order Confirmation -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-600 mb-4">Order confirmation email will be sent to {{ $userEmail }}</p>
                <button wire:click="confirmOrder" class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-4 rounded-lg font-medium {{ !$address ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$address ? 'disabled' : '' }}>
                    CONFIRM ORDER
                </button>
            </div>
        </div>

        <!-- Right Section - Sticky Summary -->
        <div class="w-full lg:w-4/12">
            <div class="sticky top-6 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-4 border-b pb-2">PRICE DETAILS</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Price ({{ $cartItems->count() }} item{{ $cartItems->count() > 1 ? 's' : '' }})</span>
                        <span>₹{{ number_format($cartItems->sum(fn($item) => $item['product']['discount_price'] * $item['quantity']), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Discount</span>
                        <span>- ₹{{ number_format($cartItems->sum(fn($item) => ($item['product']['price'] - $item['product']['discount_price']) * $item['quantity']), 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Delivery</span>
                        <span class="text-green-600">Free</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Secured Packaging Fee</span>
                        <span class="text-green-600">Free</span>
                    </div>
                </div>
                <div class="border-t mt-4 pt-4 flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>₹{{ number_format($totalAmount, 2) }}</span>
                </div>
                <p class="text-green-600 mt-4 font-medium">You will save ₹{{ number_format($cartItems->sum(fn($item) => ($item['product']['price'] - $item['product']['discount_price']) * $item['quantity']), 2) }} on this order</p>
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