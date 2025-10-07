<section class="py-5 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4 text-[#171717]">Featured Products</h2>
        <p class="text-center text-gray-600 mb-12">Discover our most popular custom printing products</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-8 px-2 sm:px-4">
            @foreach ($Products as $product)
                <div
                    class="product-card rounded-xl overflow-hidden bg-white shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg w-full max-w-[300px] mx-auto">

                    <div class="relative group">
                        <a  href="{{ route('view.product', $product->slug) }}">
                            @if ($product->firstVariantImage)
                                <div
                                    class="relative w-full aspect-[400/540] overflow-hidden bg-gray-100 flex items-center justify-center">
                                    <img src="{{ $product->firstVariantImage->image_path ?? asset('images/placeholder.jpg') }}"
                                        alt="{{ $product->firstVariantImage->name }}"
                                        class="w-full h-full object-contain object-center transition-transform duration-300 group-hover:scale-105" />
                                </div>
                            @else
                                <div
                                    class="relative w-full aspect-[370/557] bg-gray-100 flex items-center justify-center">
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder"
                                        class="w-full h-full object-contain object-center" />
                                </div>
                            @endif
                        </a>

                        <div class="absolute top-2 right-2 z-10">
                            <livewire:public.section.wishlist-button :productId="$product->id" />
                        </div>
                    </div>

                    <div class="p-3 sm:p-4 text-center">
                                                <a  href="{{ route('view.product', $product->slug) }}">

                        <h3
                            class="text-sm sm:text-base font-sans font-semibold text-[#171717] mb-1 line-clamp-2 min-h-[2.5rem] truncate">
                            {{ $product->name }}
                        </h3>

                        <div class="flex justify-center items-center gap-2 mb-3">
                            <span
                                class="text-base sm:text-lg font-bold text-[#8f4da7]">₹{{ $product->discount_price }}</span>
                            <span class="text-xs sm:text-sm text-gray-500 line-through">₹{{ $product->price }}</span>
                        </div>

                            <button
                                class="w-full bg-[#171717] text-white py-2 px-4 rounded-full text-xs sm:text-sm font-medium hover:bg-[#8f4da7] focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:ring-offset-1 transition-all duration-300 hover:scale-105">
                                <i class="fas fa-arrow-right mr-1"></i> Check It Out
                            </button>
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
