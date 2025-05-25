<div class="w-full bg-white rounded-2xl shadow-xl p-6 sm:p-8 lg:p-10">
    <!-- Product Name -->
    <h1 class="text-3xl sm:text-4xl font-extrabold mb-2 text-gray-900 tracking-tight">{{ $product->name }}</h1>
    
    <!-- Category -->
    <div class="text-base text-indigo-600 mb-4 font-semibold uppercase tracking-wide">{{ $product->category->name ?? 'Uncategorized' }}</div>

    <!-- Pricing -->
    <div class="flex items-center mb-6 space-x-4">
        <span class="text-3xl sm:text-4xl font-bold text-indigo-700">₹{{ number_format($product->discount_price ?? $product->price, 2) }}</span>
        @if($product->discount_price)
            <span class="text-lg text-gray-400 line-through">₹{{ number_format($product->price, 2) }}</span>
            <span class="text-sm text-green-700 font-semibold bg-green-100 px-3 py-1 rounded-full">
                {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
            </span>
        @endif
    </div>

    <!-- Rating -->
    <div class="flex items-center mb-6">
        <div class="flex text-yellow-500 text-lg space-x-1">
            @for($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= ($product->average_rating ?? 0) ? 'fas fa-star' : 'far fa-star' }}" aria-hidden="true"></i>
            @endfor
        </div>
        <span class="text-sm text-gray-600 ml-3">({{ $product->reviews_count ?? 0 }} reviews)</span>
    </div>

    <!-- Description -->
    <div class="text-gray-700 mb-8 leading-relaxed text-base">{{ $product->description ?? 'No description available.' }}</div>

    <!-- Variant Selection: Size -->
    @if($product->size_variants && $product->size_variants->count() > 0)
        <div class="mb-6">
            <label for="size_variant" class="block text-sm font-semibold mb-2 text-gray-800">Select Size</label>
            <select wire:model="size_variant_id" id="size_variant"
                class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50 transition duration-200">
                <option value="">Select Size</option>
                @foreach($product->size_variants as $variant)
                    <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <!-- Variant Selection: Color -->
    @if($product->color_variants && $product->color_variants->count() > 0)
        <div class="mb-6">
            <label for="color_variant" class="block text-sm font-semibold mb-2 text-gray-800">Select Color</label>
            <select wire:model="color_variant_id" id="color_variant"
                class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50 transition duration-200">
                <option value="">Select Color</option>
                @foreach($product->color_variants as $variant)
                    <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <!-- Quantity -->
    <div class="mb-8">
        <label for="quantity" class="block text-sm font-semibold mb-2 text-gray-800">Quantity</label>
        <div class="flex items-center space-x-4">
            <input type="number" wire:model="quantity" id="quantity" min="1" max="{{ $product->stock }}"
                class="w-24 p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50 transition duration-200"
                aria-describedby="stock-info">
            <span id="stock-info" class="text-sm text-gray-600">({{ $product->stock }} in stock)</span>
        </div>
        @if($product->stock == 0)
            <p class="text-sm text-red-600 mt-2">Out of stock</p>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4 mb-8">
        <button wire:click="addToCart" wire:loading.attr="disabled"
            class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 font-semibold shadow-md disabled:bg-indigo-400 disabled:cursor-not-allowed"
            aria-label="Add to Cart">
            <i class="fas fa-shopping-cart mr-2"></i>
            <span wire:loading.remove>Add to Cart</span>
            <span wire:loading>Adding...</span>
        </button>
        <button wire:click="buyNow" wire:loading.attr="disabled"
            class="flex-1 bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200 font-semibold shadow-md disabled:bg-yellow-400 disabled:cursor-not-allowed"
            aria-label="Buy Now">
            <i class="fas fa-bolt mr-2"></i>
            <span wire:loading.remove>Buy Now</span>
            <span wire:loading>Processing...</span>
        </button>
    </div>


</div>