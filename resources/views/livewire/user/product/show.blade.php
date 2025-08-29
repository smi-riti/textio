<div class="container mx-auto px-4 py-8">
    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="text-sm text-gray-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a> > 
        <a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a> > 
        <span class="text-gray-800">{{ $product->name }}</span>
    </div>
    
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Column - Product Details -->
        <div class="lg:w-2/3 bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Product Images -->
                <div class="md:w-2/5">
                    <div class="border rounded-lg p-4 mb-4 flex items-center justify-center h-64">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image max-h-56">
                        @else
                            <img src="https://via.placeholder.com/280x280?text=Product+Image" 
                                 alt="{{ $product->name }}" 
                                 class="product-image max-h-56">
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @foreach($product->images as $image)
                            <div class="border rounded p-1 w-1/4 cursor-pointer">
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                     alt="Thumbnail" 
                                     class="h-16 w-full object-contain">
                            </div>
                        @endforeach
                        @for($i = count($product->images); $i < 4; $i++)
                            <div class="border rounded p-1 w-1/4">
                                <img src="https://via.placeholder.com/80x80?text=Thumb+{{ $i+1 }}" 
                                     alt="Thumbnail {{ $i+1 }}" 
                                     class="h-16 w-full object-contain">
                            </div>
                        @endfor
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="md:w-3/5">
                    <h1 class="text-2xl font-semibold text-gray-800">{{ $product->name }}</h1>
                    <div class="flex items-center mt-2">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < floor($product->rating))
                                    <i class="fas fa-star"></i>
                                @elseif($i < ceil($product->rating) && floor($product->rating) < ceil($product->rating))
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-blue-600 ml-2">
                            {{ number_format($product->rating_count) }} Ratings & 
                            {{ number_format($product->review_count) }} Reviews
                        </span>
                    </div>
                    
                    <div class="mt-4">
                        <div class="text-2xl font-bold">₹{{ number_format($product->price - $product->discount) }}</div>
                        <div class="text-sm text-gray-600">
                            <span class="line-through">₹{{ number_format($product->price) }}</span>
                            <span class="text-green-600 ml-2">
                                {{ $product->discount > 0 ? round(($product->discount/$product->price)*100) : 0 }}% off
                            </span>
                        </div>
                    </div>
                    
                    <!-- Color Selection -->
                    @if($product->variants->where('type', 'color')->isNotEmpty())
                        <div class="mt-6">
                            <h3 class="font-semibold">Color</h3>
                            <div class="flex gap-2 mt-2">
                                @foreach($product->variants->where('type', 'color') as $variant)
                                    <button 
                                        wire:click="selectColor('{{ $variant->value }}')"
                                        class="border rounded p-2 {{ $selectedColor === $variant->value ? 'border-blue-600 border-2' : 'border-gray-300' }}"
                                    >
                                        {{ $variant->value }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Storage Selection -->
                    @if($product->variants->where('type', 'storage')->isNotEmpty())
                        <div class="mt-6">
                            <h3 class="font-semibold">Storage</h3>
                            <div class="flex gap-2 mt-2">
                                @foreach($product->variants->where('type', 'storage') as $variant)
                                    <button 
                                        wire:click="selectStorage('{{ $variant->value }}')"
                                        class="border rounded p-2 {{ $selectedStorage === $variant->value ? 'border-blue-600 border-2' : 'border-gray-300' }}"
                                    >
                                        {{ $variant->value }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Quantity Selection -->
                    <div class="mt-6">
                        <h3 class="font-semibold">Quantity</h3>
                        <div class="flex items-center mt-2">
                            <button wire:click="decrement" class="border rounded-l px-3 py-1 bg-gray-100">
                                -
                            </button>
                            <input type="number" 
                                   wire:model="quantity" 
                                   class="w-12 text-center border-t border-b py-1 quantity-input">
                            <button wire:click="increment" class="border rounded-r px-3 py-1 bg-gray-100">
                                +
                            </button>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-4">
                        <button 
                            wire:click="addToCart"
                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-lg font-semibold shadow-md"
                        >
                            <i class="fas fa-shopping-cart mr-2"></i>ADD TO CART
                        </button>
                        <button class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold shadow-md">
                            <i class="fas fa-bolt mr-2"></i>BUY NOW
                        </button>
                    </div>
                    
                    <!-- Delivery Info -->
                    <div class="mt-6 p-4 border rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-truck text-gray-500 mr-2"></i>
                            <div>
                                <span class="font-semibold">Delivery</span>
                                <p class="text-sm text-gray-600">
                                    Enter your PIN code for delivery estimation
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Details -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold border-b pb-2">Product Details</h2>
                <div class="mt-4">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
        
        <!-- Right Column - Price Details -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">PRICE DETAILS</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Price ({{ $quantity }} item{{ $quantity > 1 ? 's' : '' }})</span>
                        <span>₹{{ number_format($product->price * $quantity) }}</span>
                    </div>
                    
                    @if($product->discount > 0)
                    <div class="flex justify-between">
                        <span>Discount</span>
                        <span class="text-green-600">-₹{{ number_format($product->discount * $quantity) }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span>Delivery Charges</span>
                        @if($product->delivery_charge > 0)
                            <span>₹{{ number_format($product->delivery_charge) }}</span>
                        @else
                            <span class="text-green-600">FREE</span>
                        @endif
                    </div>
                    
                    <div class="flex justify-between text-lg font-semibold border-t pt-3 mt-3">
                        <span>Total Amount</span>
                        <span>₹{{ number_format(($product->price - $product->discount) * $quantity + $product->delivery_charge) }}</span>
                    </div>
                </div>
                
                @if($product->discount > 0)
                <div class="mt-4 text-green-600 font-semibold">
                    You will save ₹{{ number_format($product->discount * $quantity) }} on this order
                </div>
                @endif
                
                <!-- Delivery Assurance -->
                <div class="mt-6 p-3 bg-green-50 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-shield-alt text-green-600 mt-1 mr-2"></i>
                        <div>
                            <p class="text-sm font-semibold">Safe and Secure Payments</p>
                            <p class="text-xs text-gray-600">Easy returns. 100% Authentic products.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Services -->
                <div class="mt-6 space-y-3">
                    <div class="flex items-center">
                        <i class="fas fa-undo-alt text-gray-500 mr-2"></i>
                        <span class="text-sm">14 Days Return Policy</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-gray-500 mr-2"></i>
                        <span class="text-sm">Warranty Available</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-truck text-gray-500 mr-2"></i>
                        <span class="text-sm">Free Delivery on orders above ₹499</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-image {
        transition: transform 0.3s;
    }
    .product-image:hover {
        transform: scale(1.05);
    }
    .quantity-input {
        -moz-appearance: textfield;
    }
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Livewire initialization if needed
    });
</script>
@endpush