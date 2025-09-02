<div class="">
    <!-- Hero Section with Carousel -->
    <livewire:public.section.hero-section/>

    <!-- Featured Categories Section -->
    <livewire:public.section.categories/>

    <!-- Featured Products Section -->
    <livewire:public.section.products/>
    @if(!$moreProducts->isEmpty())
     <!-- Additional Products Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center mb-12">More Custom Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($moreProducts as $product)
                    <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                        <div class="relative">
                                <a wire:navigate href="{{ route('view.product', $product->slug) }}">
                                    <img src="{{ $product->images->first()?->image_path ?? asset('images/placeholder.jpg') }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-56 object-cover lazy" loading="lazy">
                                </a>
                                <div
                            class="absolute top-3 right-3 hover:shadow text-xs font-semibold rounded-full uppercase tracking-wide">
                            <livewire:public.section.wishlist-button :productId="$product->id" />
                        </div>
                                
                                <span class="absolute top-2 left-2 bg-purple-600 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    Personalize
                                </span>
                            </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 60) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex justify-center items-center gap-3 mb-6">
                                <span class="font-semibold text-purple-600">₹{{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                            <span class="text-sm text-gray-400 line-through">₹{{ $product->price }}</span>
                        </div>
                                <button class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                                        @click="cartItems++" aria-label="Add {{ $product->name }} to cart">
                                    <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <!-- Newsletter Section -->
    <section class="py-12 bg-purple-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-semibold mb-4">Stay Updated</h2>
            <p class="mb-8 max-w-2xl mx-auto">Subscribe to our newsletter for exclusive deals, new product announcements, and design tips.</p>
            <form class="max-w-md mx-auto flex flex-col md:flex-row gap-4" @submit.prevent="handleNewsletterSubmit">
                <label for="email" class="sr-only">Email Address</label>
                <input id="email" type="email" placeholder="Your email address" class="flex-grow px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300" required>
                <button type="submit" class="bg-white text-purple-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition duration-300" aria-label="Subscribe to newsletter">Subscribe</button>
            </form>
        </div>
    </section>

</div>