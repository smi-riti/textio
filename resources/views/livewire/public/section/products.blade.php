<section class="py-5 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4 text-[#171717]">Featured Products</h2>
        <p class="text-center text-gray-600 mb-12">Discover our most popular custom printing products</p>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-5 px-3 sm:px-4">
            @foreach ($Products as $product)
                <div
                    class="bg-white border border-[#dedada] rounded-lg overflow-hidden w-full mx-auto transition-all duration-300 ">

                    <!-- Image Section -->
                    <div class="relative p-2 sm:p-3">
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
                        <div class="absolute top-3 right-3 sm:top-4 sm:right-4">
                            <livewire:public.section.wishlist-button :productId="$product->id" />
                        </div>
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



        <div class="text-center mt-12">
            <a href="{{ route('public.product.all') }}"
                class="inline-flex items-center hover:bg-[#8f4da7] bg-[#171717] text-white font-medium py-3 px-8 rounded-full transition-all duration-300">
                View All Products
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
