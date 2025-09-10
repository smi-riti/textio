<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @if ($categories->isNotEmpty())
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-semibold text-[#171717] mb-4">Product Categories</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Find exactly what you're looking for</p>
            </div>

            <!-- Rounded Icon Cards - Show only 3 categories -->
            <div class="grid grid-cols-3 gap-4 md:gap-8 justify-center">
                @foreach ($categories as $category)
                    <div class="group text-center">
                        <a href="#" class="block">
                            <div class="relative mx-auto mb-4 w-20 h-20 sm:w-24 sm:h-24 md:w-52 md:h-52 rounded-full bg-[#f5f0ff] flex items-center justify-center transition-all duration-300 group-hover:bg-[#8f4da7] group-hover:shadow-lg group-hover:scale-110 overflow-hidden">
                                <img src="{{  $category->image}}"
                                     alt="{{ $category->title }}"
                                     class="h-full w-full object-cover transition-all duration-300 group-hover:brightness-75"
                                     loading="lazy">
                                <span class="absolute -top-1 -right-1 bg-[#8f4da7] text-white text-xs font-semibold rounded-full w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center shadow-md">
                                    {{ $category->products_count ?? '0' }}
                                </span>
                            </div>
                            <h3 class="text-[#171717] text-sm font-sans sm:text-base md:text-sm  group-hover:text-[#8f4da7] transition-colors duration-300 line-clamp-2 px-2">
                                {{ $category->title }}
                            </h3>
                        </a>
                    </div>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="text-center mt-12">
                <a wire:navigate href="{{route('public.product.all')}}" class="inline-flex items-center bg-[#171717] hover:bg-[#8f4da7] text-white font-medium py-3 px-8 rounded-full transition-all duration-300">
                    <span>Explore All Categories</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        @endif
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</section>