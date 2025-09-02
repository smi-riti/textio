
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 mb-6">My Cart</h1>
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Column - Cart Items -->
        <div class="w-full lg:w-8/12">
            <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
                @forelse ($cartItems as $item)
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 mb-6 border-b pb-4">
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            <div class="w-32 h-32 sm:w-40 sm:h-40 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                @if($item->product->images && $item->product->images->first())
                                    <img src="{{ $item->product->images->first()->image_path }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-image text-gray-400 text-3xl sm:text-4xl"></i>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 truncate">{{ $item->product->name ?? 'Unknown Product' }}</h2>
                                    <p class="text-gray-600 text-sm mt-1">{{ $item->productVariant->variant_name ?? 'Free Size' }}</p>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-1">Seller: RetailNet</p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <span class="text-lg sm:text-2xl font-bold text-gray-900">₹{{ number_format($item->product->discount_price * $item->quantity, 0) }}</span>
                                    @if($item->product->price > $item->product->discount_price)
                                        <del class="text-sm sm:text-lg text-gray-500">₹{{ number_format($item->product->price * $item->quantity, 0) }}</del>
                                        <span class="text-green-600 text-sm sm:text-base font-semibold">
                                            @if($item->product->price > 0)
                                                {{ round((($item->product->price - $item->product->discount_price) / $item->product->price) * 100) }}% Off
                                            @else
                                                0% Off
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="flex gap-4 sm:gap-6 items-center mt-4">
                                <div class="flex items-center">
                                    <span class="text-gray-700 text-sm mr-2 sm:mr-3">Quantity:</span>
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button wire:click="decreaseQuantity({{ $item->id }})" class="px-2 sm:px-3 py-1 text-gray-600 hover:text-purple-600 transition">
                                            <i class="fas fa-minus text-sm"></i>
                                        </button>
                                        <span class="px-3 sm:px-4 py-1 text-gray-800 font-medium text-sm">{{ $item->quantity }}</span>
                                        <button wire:click="increaseQuantity({{ $item->id }})" class="px-2 sm:px-3 py-1 text-gray-600 hover:text-purple-600 transition">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                                <button wire:click="removeItem({{ $item->id }})" class="text-red-500 hover:text-red-700 transition">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 text-center py-6">Your cart is empty.</p>
                @endforelse
            </div>
         @if($cartItems->isNotEmpty())
    <div class="bg-white hidden sm:flex rounded-lg justify-end shadow p-4 mb-6">
        <button wire:click="placeOrder"
            class="bg-purple-500 hover:bg-purple-600 text-white py-3 px-20 rounded text-xl flex items-center justify-center">
            Place Order
        </button>
    </div>
@endif


        </div>
        
        <!-- Right Column - Order Summary -->
        <div class="w-full lg:w-4/12">
            <div class="bg-white shadow-md rounded-lg p-4 sm:p-6 sticky top-10">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 border-b pb-2">Price Details</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm sm:text-base">
                        <span class="text-gray-600">Price ({{ $cartItems->count() }} item{{ $cartItems->count() > 1 ? 's' : '' }})</span>
                        <span class="text-gray-800">₹{{ number_format($cartItems->sum(fn($item) => $item->product->discount_price * $item->quantity), 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm sm:text-base">
                        <span class="text-gray-600">Discount</span>
                        <span class="text-green-600">₹{{ number_format($cartItems->sum(fn($item) => ($item->product->price - $item->product->discount_price) * $item->quantity), 0) }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Delivery</span>
                        <span>FREE</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-4 pt-4">
                    <div class="flex justify-between font-semibold text-base sm:text-lg">
                        <span class="text-gray-900">Total Amount</span>
                        <span class="text-gray-900">₹{{ number_format($cartItems->sum(fn($item) => $item->product->discount_price * $item->quantity), 0) }}</span>
                    </div>
                    <p class="text-green-600 mt-2 text-sm">You will save ₹{{ number_format($cartItems->sum(fn($item) => ($item->product->price - $item->product->discount_price) * $item->quantity), 0) }} on this order</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Fixed Place Order Button -->
    @if($cartItems->isNotEmpty())
        <div class="lg:hidden fixed mb-20 bottom-0 left-0 right-0 bg-white shadow-lg p-4 z-10">
            <div class="container mx-auto flex justify-end">
                <button wire:click="placeOrder" class="bg-purple-600 hover:bg-purple-700 text-white py-2 sm:py-3 px-6 sm:px-8 rounded-lg font-semibold text-base sm:text-lg transition w-full sm:w-auto">
                    Place Order
                </button>
            </div>
        </div>
    @endif

    
</div>
