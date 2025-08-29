<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a> > 
        <a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a> > 
        <span class="text-gray-800">{{ $product->name }}</span>
    </div>

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
                <div class="text-2xl font-bold">₹{{ number_format($price - $discount) }}</div>
                <div class="text-sm text-gray-600">
                    <span class="line-through">₹{{ number_format($price) }}</span>
                    <span class="text-green-600 ml-2">
                        {{ $discount > 0 ? round(($discount/$price)*100) : 0 }}% off
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