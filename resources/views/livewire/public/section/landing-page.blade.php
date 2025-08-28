<div class="">
    <!-- Hero Section with Carousel -->
    <livewire:public.section.hero-section/>

    <!-- Featured Categories Section -->
    <livewire:public.section.categories/>

    <!-- Featured Products Section -->
    <livewire:public.section.products/>

     <!-- Additional Products Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center mb-12">More Custom Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach([
                    [
                        'name' => 'Custom Stickers',
                        'description' => 'Waterproof and durable vinyl stickers',
                        'price' => '₹9.95 - ₹39.95',
                        'image' => 'https://images.unsplash.com/photo-1584824486539-53bb4646bdbc?ixlib=rb-4.0.3&auto=format&fit=crop&w=687&q=80',
                    ],
                    [
                        'name' => 'Custom Badges',
                        'description' => 'Pin-back or magnetic custom badges',
                        'price' => '₹9.90 - ₹49.90',
                        'image' => 'https://images.unsplash.com/photo-1616634375264-2d2e17736a36?ixlib=rb-4.0.3&auto=format&fit=crop&w=715&q=80',
                    ],
                    [
                        'name' => 'Phone Cases',
                        'description' => 'Durable cases for all phone models',
                        'price' => '₹19.95 - ₹24.95',
                        'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-4.0.3&auto=format&fit=crop&w=880&q=80',
                    ],
                    [
                        'name' => 'Mouse Pads',
                        'description' => 'Non-slip rubber base mouse pads',
                        'price' => '₹14.90 - ₹99.90',
                        'image' => 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80',
                    ]
                ] as $product)
                    <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                        <div class="relative">
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }} - {{ $product['description'] }}" class="w-full h-56 object-cover lazy" loading="lazy">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">{{ $product['name'] }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $product['description'] }}</p>
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-purple-600">{{ $product['price'] }}</span>
                                <button class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                                        @click="cartItems++" aria-label="Add {{ $product['name'] }} to cart">
                                    <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

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