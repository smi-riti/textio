<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4">Your Wishlist</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($wishlistItems as $item)
                <div class="product-card rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 max-w-sm">
                    <div class="relative">
                        <img src="{{ $item->product->images->first()?->image_path ?? asset('images/placeholder.jpg') }}"
                             alt="{{ $item->product->name }}"
                             class="w-full h-64 object-cover">
                        <div class="absolute top-3 right-3 hover:shadow text-xs font-semibold rounded-full uppercase tracking-wide">
                            <button class="p-2 rounded-full bg-white text-red-500"
                                    wire:click="removeFromWishlist({{ $item->product->id }})">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2 truncate">{{ $item->product->name }}</h3>
                        <div class="flex justify-center items-center gap-3 mb-6">
                            <span class="text-lg font-bold text-purple-600">₹{{ $item->product->price }}</span>
                            <span class="text-sm text-gray-400 line-through">₹{{ $item->product->discount_price }}</span>
                        </div>
                        <button class="w-full bg-gray-800 text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-purple-600 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-600">Your wishlist is empty.</p>
            @endforelse
        </div>
    </div>
</section>
