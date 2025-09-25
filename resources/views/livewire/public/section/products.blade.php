<section class="py-5 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4 text-[#171717]">Featured Products</h2>
        <p class="text-center text-gray-600 mb-12">Discover our most popular custom printing products</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach ($Products as $product)
                <div
                    class="product-card  rounded-xl overflow-hidden  transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl max-w-sm">
                    <div class="relative">
                        <a wire:navigate href="{{ route('view.product', $product->slug) }}">
                            @if ($product->firstVariantImage)
                                <img src="{{ $product->firstVariantImage->image_path ?? asset('images/placeholder.jpg') }}"
                                    alt="{{ $product->firstVariantImage->name }}" class="w-full h-64 object-cover">
                            @else
                            <h1>hello</h1>
                            @endif
                        </a>
                        <div class="absolute top-3 right-3 text-xs font-semibold rounded-full uppercase tracking-wide">
                            <livewire:public.section.wishlist-button :productId="$product->id" />
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-sans font-semibold text-[#171717] mb-2 truncate">{{ $product->name }}
                        </h3>
                        <div class="flex justify-center items-center gap-3 mb-6">
                            <span class="text-lg font-bold text-[#8f4da7]">₹{{ $product->discount_price }}</span>
                            <span class="text-sm text-gray-400 line-through">₹{{ $product->price }}</span>
                        </div>
                        <a wire:navigate href="{{ route('view.product', $product->slug) }}">
                            <button
                                class="add-to-cart-btn w-full bg-[#171717] text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-[#8f4da7] focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-arrow-right mr-2"></i> check it out
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
