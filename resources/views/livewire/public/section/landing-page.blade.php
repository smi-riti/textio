<div class="">
    <!-- Hero Section with Carousel -->
    <livewire:public.section.hero-section />

    <!-- Featured Categories Section -->
    <livewire:public.section.categories />


    <!-- Featured Products Section -->
    <livewire:public.section.products />
    <section class="py-20 px-4 bg-[#f8f5ff]">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black tracking-tight mb-6 text-[#171717]">WHY CHOOSE US?</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto font-light">Simple, fast, and premium
                    quality results that exceed expectations.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-10 md:gap-16">
                <!-- Feature 1 -->
                <div class="text-center bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#f5f0ff] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="#8f4da7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="13.5" cy="6.5" r=".5" fill="#8f4da7"></circle>
                                <circle cx="17.5" cy="10.5" r=".5" fill="#8f4da7"></circle>
                                <circle cx="8.5" cy="7.5" r=".5" fill="#8f4da7"></circle>
                                <circle cx="6.5" cy="12.5" r=".5" fill="#8f4da7"></circle>
                                <path
                                    d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-[#171717]">CUSTOM DESIGN</h3>
                    <p class="text-gray-600 leading-relaxed">Create unique designs with our easy-to-use customization
                        tools and professional templates.</p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#f5f0ff] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="#8f4da7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                <path d="M15 18H9"></path>
                                <path
                                    d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                                </path>
                                <circle cx="17" cy="18" r="2"></circle>
                                <circle cx="7" cy="18" r="2"></circle>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-[#171717]">FAST DELIVERY</h3>
                    <p class="text-gray-600 leading-relaxed">Quick processing and shipping to get your products when you
                        need them, without delays.</p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#f5f0ff] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="#8f4da7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-[#171717]">PREMIUM QUALITY</h3>
                    <p class="text-gray-600 leading-relaxed">High-quality materials and professional printing techniques
                        for exceptional, lasting results.</p>
                </div>
            </div>
        </div>
    </section>
    @if (!$moreProducts->isEmpty())
        <!-- Additional Products Section -->
       <section class="py-12 bg-white font-sans">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-12 text-[#171717]">More Custom Products</h2>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3">
            @foreach ($moreProducts as $product)
                <div class="bg-white border border-[#dedada] rounded-lg overflow-hidden w-full mx-auto transition-all duration-300">
                    <!-- Image Section -->
                    <div class="relative p-1 sm:p-2">
                       <a href="{{ route('view.product', $product->slug) }}">
                            @if ($product->firstVariantImage)
                                <div
                                    class="aspect-[357/557] bg-gray-50 flex items-center justify-center overflow-hidden rounded-md">
                                    <img src="{{ $product->firstVariantImage->image_path ?? asset('images/placeholder.jpg') }}"
                                        alt="{{ $product->firstVariantImage->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                                </div>
                            @else
                                <div class="aspect-[357/557] bg-gray-50 flex items-center justify-center rounded-md">
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder"
                                        class="w-full h-full object-cover" />
                                </div>
                            @endif
                        </a>
                        <!-- Wishlist Button -->
                        <div class="absolute top-2 right-2 sm:top-3 sm:right-3">
                            <livewire:public.section.wishlist-button :productId="$product->id" />
                        </div>
                        <!-- Personalize Badge -->
                        <span class="absolute top-2 left-2 bg-[#8f4da7] text-white text-[10px] sm:text-xs font-semibold px-2 py-0.5 sm:py-1 rounded-full">
                            Personalize
                        </span>
                    </div>
                    <!-- Content Section -->
                   <div class="p-2 sm:p-4">
                        <a href="{{ route('view.product', $product->slug) }}">
                            <!-- Price Section -->
                            <div class="flex items-center justify-center gap-1 sm:gap-2 mb-2 sm:mb-3 flex-wrap">
                                <span class="text-sm sm:text-lg font-semibold text-[#8f4da7]">
                                    ₹{{ $product->discount_price }}
                                </span>
                                <span class="text-xs sm:text-sm text-[#8A8E92] line-through">
                                    ₹{{ $product->price }}
                                </span>

                                @php
                                    $discount = 0;
                                    if ($product->price > 0 && $product->discount_price < $product->price) {
                                        $discount = round(
                                            (($product->price - $product->discount_price) / $product->price) * 100,
                                        );
                                    }
                                @endphp

                                @if ($discount > 0)
                                    <span
                                        class="text-xs font-semibold text-green-600 bg-green-50 px-1 sm:px-2 py-0.5 sm:py-1 rounded-full">
                                        {{ $discount }}% OFF
                                    </span>
                                @endif
                            </div>

                            <!-- Product Name -->
                            <h3
                                class="text-[#3e3f40] text-xs sm:text-xm font-medium text-center   line-clamp-2 min-h-[2rem] sm:min-h-[2rem] leading-tight truncate ">
                                {{ $product->name }}
                            </h3>
                            <!-- CTA Button -->
                            <div
                                class="border border-[#8f4da7] text-[#8f4da7] hover:bg-[#8f4da7] hover:text-white py-1.5 sm:py-2 px-2 sm:px-4 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 text-center">
                                <i class="fas fa-arrow-right mr-1 sm:mr-2"></i>Check It Out
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</section>
    @endif
    <!-- Newsletter Section -->
    {{-- <section class="py-16 bg-[#8f4da7] text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
        <p class="mb-8 max-w-2xl mx-auto text-purple-100">Subscribe to our newsletter for exclusive deals, new product announcements, and design tips.</p>
        <form class="max-w-md mx-auto flex flex-col md:flex-row gap-3" @submit.prevent="handleNewsletterSubmit">
            <label for="email" class="sr-only">Email Address</label>
            <input id="email" type="email" placeholder="Your email address" 
                   class="flex-grow px-5 py-3.5 rounded-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#171717] focus:ring-offset-2" required>
            <button type="submit" class="bg-[#171717] text-white font-semibold px-6 py-3.5 rounded-full hover:bg-[#2c2c2c] transition duration-300 flex-shrink-0" 
                    aria-label="Subscribe to newsletter">
                Subscribe
            </button>
        </form>
        <p class="mt-4 text-sm text-purple-200 max-w-md mx-auto">We respect your privacy. Unsubscribe at any time.</p>
    </div>
</section> --}}



</div>
