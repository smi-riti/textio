<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4">Featured Products</h2>
        <p class="text-center text-gray-600 mb-12">Discover our most popular custom printing products</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach ($Products as $product)
                <div class="product-card rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 max-w-sm">
                    <div class="relative">
                        <a wire:navigate href="{{ route('view.product', $product->slug) }}">
                            <img src="{{asset('storage/' .$product->images->first()?->image_path) ?? asset('images/placeholder.jpg') }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-64 object-cover">
                        </a>
                        <div class="absolute top-3 right-3 hover:shadow text-xs font-semibold rounded-full uppercase tracking-wide">
                            <livewire:public.section.wishlist-button :productId="$product->id" />
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2 truncate">{{ $product->name }}</h3>
                        <div class="flex justify-center items-center gap-3 mb-6">
                            <span class="text-lg font-bold text-purple-600">₹{{ $product->price }}</span>
                            <span class="text-sm text-gray-400 line-through">₹{{ $product->discount_price }}</span>
                        </div>
                        <button class="add-to-cart-btn w-full bg-gray-800 text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('wishlist.index') }}"
               class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300">
                View Products
            </a>
        </div>
    </div>
</section>