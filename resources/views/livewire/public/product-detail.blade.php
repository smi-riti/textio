<div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-12 py-8 mt-[70px]">
    <!-- Product Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Product Image -->
        <div class="px-7 h-[500px]">
            <img src="{{ asset('image/product/' . $product->images->first()->path) }}"
                alt="{{ e($product->name) }}"
                class="w-full h-[370px] object-contain rounded-lg shadow-md">
            <div class="flex mt-2 gap-3 items-center">
                @foreach ($product->images->skip(1) as $image)
                    <img src="{{ asset('image/product/' . $image->path) }}"
                        alt="{{ e($product->name) }}"
                        class="w-45 h-45 object-cover rounded-md">
                @endforeach
            </div>
        </div>
        <div class="flex flex-col justify-start gap-4 h-full">
            <!-- Product Title and Price -->
            <div class="p-2">
                <div class="flex flex-col justify-between items-start">
                    <div class="flex w-full flex-col gap-2">
                        <h1 class="text-2xl font-semibold mb-1">{{ e($product->name) }}</h1>
                        <p class="text-2xl text-gray-800 font-bold">₹{{ $product->price }}</p>
                    </div>
                    <div class="flex w-full items-center justify-between mt-2">
                        <div class="flex">
                            <div class="text-yellow-500">
                                <span class="text-xl">★</span>
                                <span class="text-xl">★</span>
                                <span class="text-xl">★</span>
                                <span class="text-xl">★</span>
                                <span class="text-gray-300 text-xl">★</span>
                            </div>
                            <span class="ml-2 text-gray-600">(120 reviews)</span>
                        </div>
                        <div class="mr-5">
                            {{-- <livewire:wishlist-toggle :productId="$product->id" /> --}}
                        </div>
                    </div>
                </div>
                <h2 class="mt-2"><span class="font-semibold text-gray-600">Category: </span>{{ $product->category->cat_title }}</h2>
                <p class="text-gray-700 mt-4">{{ e($product->description) }}</p>

                <!-- Color Options -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-800">Choose Color</h3>
                    <div class="flex space-x-4 mt-2">
                        @foreach ($product->variants as $item)
                            @if ($item->variant_type === 'color')
                                <button
                                    wire:click="$set('color_variant_id', {{ $item->id }})"
                                    class="w-8 h-8 rounded-full border-2 {{ $color_variant_id == $item->id ? 'border-blue-600' : 'border-gray-300' }} focus:outline-none transition-transform transform hover:scale-105"
                                    style="background-color: {{ $item->variant_name }};"
                                    aria-label="Choose color {{ $item->variant_name }}"
                                    value="{{ $item->variant_name }}">
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Size Options -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-800">Choose Size</h3>
                    <div class="flex space-x-4 mt-2">
                        @foreach ($product->variants as $item)
                            @if ($item->variant_type === 'size')
                                <button
                                    wire:click="$set('size_variant_id', {{ $item->id }})"
                                    class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md border {{ $size_variant_id == $item->id ? 'border-blue-600 bg-blue-100' : 'border-gray-300' }} focus:outline-none transition duration-200 hover:bg-gray-200"
                                    aria-label="Choose size {{ $item->variant_name }}"
                                    value="{{ $item->variant_name }}">
                                    {{ $item->variant_name }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Quantity Input -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-800">Quantity</h3>
                    <div class="flex items-center mt-2">
                        <input
                            type="number"
                            wire:model="quantity"
                            min="1"
                            class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                            aria-label="Select quantity"
                        >
                    </div>
                    @error('quantity')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Add to Cart Button -->
                <div class="mt-8">
                    <button
                        wire:click="addToCart"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-500 transition duration-200"
                        aria-label="Add {{ $product->name }} to cart">
                        Add to Cart
                    </button>
                </div>

                <!-- Buy Now Button -->
                <div class="mt-4">
                    <button
                        wire:click="buyNow"
                        class="w-full bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-500 transition duration-200"
                        aria-label="Buy {{ $product->name }} now">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Description, Reviews, FAQs -->
    {{-- <livewire:public.product-lower-section :productId="$product->id" /> --}}

    <!-- Related Products Section -->
  
</div>