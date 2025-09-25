<div class="max-w-7xl mb-16 md:mb-0 mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{ mobileFiltersOpen: false }" wire:ignore.self
    @close-mobile-filters.window="if (window.innerWidth < 1024) { mobileFiltersOpen = false }">

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (session('wishlist_message'))
        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('wishlist_message') }}</span>
        </div>
    @endif

    @if (session('wishlist_error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('wishlist_error') }}</span>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Left Sidebar - Filters -->
        <div class="w-full lg:w-1/4">
            <!-- Mobile Filter Toggle -->
            <div class="lg:hidden mb-4">
                <button @click="mobileFiltersOpen = !mobileFiltersOpen"
                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span x-text="mobileFiltersOpen ? 'Hide Filters' : 'Show Filters'"></span>
                    <svg class="w-4 h-4 ml-2 transition-transform" :class="{ 'rotate-180': mobileFiltersOpen }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <!-- Filters Container -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm"
                :class="{ 'hidden lg:block': !mobileFiltersOpen, 'block': mobileFiltersOpen }">

                <!-- Search Bar -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Search</h6>
                    <div class="relative">
                        <input type="text"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7]"
                            placeholder="Search products..." wire:model.live.debounce.300ms="searchQuery">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Product Categories -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Product categories</h6>
                    <!-- All Categories Option -->
                    <div class="mb-2">
                        <button wire:click="resetFilters" class="text-[#8f4da7] hover:underline text-sm font-medium">All Categories</button>
                    </div>
                    <!-- Parent Categories -->
                    @php
                        $categoriesToShow = $showAllCategories ? $parentCategories : $parentCategories->take(8);
                    @endphp
                    @foreach ($categoriesToShow as $category)
                        <div class="mb-1">
                            <button wire:click="$set('tempSelectedCategory', {{ $category->id }})"
                                class="w-full text-left px-2 py-1 rounded transition-colors {{ $tempSelectedCategory == $category->id ? 'bg-[#8f4da7] text-white font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                {{ $category->title }}
                            </button>
                        </div>
                    @endforeach
                    <!-- See More/Less for Categories -->
                    @if ($parentCategories->count() > 8)
                        <button wire:click="toggleShowAllCategories" class="text-xs text-[#8f4da7] mt-2 font-medium">
                            {{ $showAllCategories ? 'Show Less' : 'See More' }}
                        </button>
                    @endif

                    <!-- Subcategories (only if parent selected) -->
                    @if (!empty($subcategories) && count($subcategories))
                        <div class="mt-4">
                            <h6 class="text-sm font-semibold text-gray-700 mb-2">Subcategories</h6>
                            @foreach ($subcategories as $subcat)
                                <div class="mb-1">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:click="toggleTempSubcategory({{ $subcat->id }})"
                                            @if (in_array($subcat->id, $tempSelectedSubcategories)) checked @endif
                                            class="text-[#8f4da7] focus:ring-[#8f4da7] rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ $subcat->title }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Brand Filter -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Brands</h6>

                    @php
                        $brandsToShow = $showAllBrands ? $brands : $brands->take(8);
                    @endphp

                    <div class="space-y-2">
                        @foreach ($brandsToShow as $brand)
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:click="toggleTempBrand({{ $brand->id }})"
                                    @if (in_array($brand->id, $tempSelectedBrands)) checked @endif
                                    class="text-[#8f4da7] focus:ring-[#8f4da7] rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- See More/Less for Brands -->
                    @if ($brands->count() > 8)
                        <button wire:click="toggleShowAllBrands"
                            class="text-[#8f4da7] text-sm font-medium hover:text-[#7a3d8f] mt-2">
                            {{ $showAllBrands ? 'See Less' : 'See More (' . ($brands->count() - 8) . ' more)' }}
                        </button>
                    @endif
                </div>

                <!-- Price Filter -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-4">Filter by price</h6>

                    <!-- Price Range Slider -->
                    <div class="relative mb-6" x-data="{
                        tempMinPrice: @entangle('tempMinPrice'),
                        tempMaxPrice: @entangle('tempMaxPrice'),
                        minRange: 0,
                        maxRange: 10000,
                        updateSlider() {
                            let minPercent = (this.tempMinPrice / this.maxRange) * 100;
                            let maxPercent = (this.tempMaxPrice / this.maxRange) * 100;
                            this.$refs.progress.style.left = minPercent + '%';
                            this.$refs.progress.style.width = (maxPercent - minPercent) + '%';
                        }
                    }" x-init="updateSlider()" @input="updateSlider()">

                        <!-- Slider Track -->
                        <div class="relative h-2 bg-gray-200 rounded-full">
                            <!-- Active Range -->
                            <div x-ref="progress" class="absolute h-2 bg-[#8f4da7] rounded-full"></div>
                        </div>

                        <!-- Range Inputs -->
                        <div class="relative">
                            <input type="range" :min="minRange" :max="maxRange" x-model="tempMinPrice"
                                class="absolute w-full h-2 bg-transparent appearance-none pointer-events-none [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-[#8f4da7] [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:cursor-pointer [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-[#8f4da7] [&::-moz-range-thumb]:border-none [&::-moz-range-thumb]:cursor-pointer">
                            <input type="range" :min="minRange" :max="maxRange" x-model="tempMaxPrice"
                                class="absolute w-full h-2 bg-transparent appearance-none pointer-events-none [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-[#8f4da7] [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:cursor-pointer [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-[#8f4da7] [&::-moz-range-thumb]:border-none [&::-moz-range-thumb]:cursor-pointer">
                        </div>

                        <!-- Price Display -->
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-sm text-gray-600">
                                ₹<span x-text="tempMinPrice"></span> - ₹<span x-text="tempMaxPrice"></span>+
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Color Filter -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Filter by Color</h6>
                    <!-- Debug info -->
                    @if (empty($availableColors))
                        <p class="text-sm text-gray-500 italic">No colors available</p>
                    @endif
                    <div class="flex flex-wrap gap-2">
                        @foreach ($availableColors as $color)
                            <button wire:click="toggleTempColor('{{ $color }}')"
                                class="px-3 py-1 rounded border text-sm transition-colors {{ in_array($color, $tempSelectedColors) ? 'bg-[#8f4da7] text-white border-[#8f4da7]' : 'bg-white text-gray-700 border-gray-300 hover:border-[#8f4da7]' }}">
                                {{ ucfirst($color) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Size Filter -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Filter by Size</h6>
                    <!-- Debug info -->
                    @if (empty($availableSizes))
                        <p class="text-sm text-gray-500 italic">No sizes available</p>
                    @endif
                    <div class="flex flex-wrap gap-2">
                        @foreach ($availableSizes as $size)
                            <button wire:click="toggleTempSize('{{ $size }}')"
                                class="px-3 py-1 rounded border text-sm transition-colors {{ in_array($size, $tempSelectedSizes) ? 'bg-[#8f4da7] text-white border-[#8f4da7]' : 'bg-white text-gray-700 border-gray-300 hover:border-[#8f4da7]' }}">
                                {{ strtoupper($size) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Apply All Filters Button -->
                <div class="p-4 border-t border-gray-200">
                    <button wire:click="applyAllFilters"
                        class="w-full bg-[#8f4da7] text-white py-2 rounded hover:bg-[#7a3d8f] transition font-medium">Apply Filters</button>
                </div>

                <!-- Clear Filters -->
                @if (
                    $searchQuery ||
                        !empty($selectedSubcategories) ||
                        !empty($selectedBrands) ||
                        ($minPrice > 0 || $maxPrice < 10000) ||
                        !empty($selectedColors) ||
                        !empty($selectedSizes) ||
                        $selectedCategory)
                    <div class="p-4">
                        <button wire:click="resetFilters" class="text-red-600 hover:underline text-sm font-medium">Clear All Filters</button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Content - Products -->
        <div class="flex-1">
            <!-- Results Header & Sort -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of
                        {{ $products->total() }} results
                    </h2>
                </div>

                <!-- Sort Dropdown -->
                {{-- <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#8f4da7]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        @switch($sortBy)
                            @case('price_low')
                                Default sorting
                            @break

                            @case('price_high')
                                Sort by price: high to low
                            @break

                            @case('name')
                                Sort by name
                            @break

                            @case('discount')
                                Sort by discount
                            @break

                            @case('latest')
                                Sort by latest
                            @break

                            @default
                                Default sorting
                        @endswitch
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#8f4da7] transition-colors"
                                wire:click="$set('sortBy', 'popularity')" @click="open = false">Sort by popularity</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#8f4da7] transition-colors"
                                wire:click="$set('sortBy', 'latest')" @click="open = false">Sort by latest</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#8f4da7] transition-colors"
                                wire:click="$set('sortBy', 'price_low')" @click="open = false">Sort by price: low to high</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#8f4da7] transition-colors"
                                wire:click="$set('sortBy', 'price_high')" @click="open = false">Sort by price: high to low</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#8f4da7] transition-colors"
                                wire:click="$set('sortBy', 'name')" @click="open = false">Sort by name</a>
                        </div>
                    </div>
                </div> --}}
            </div>

            <!-- Products List (Row Layout) -->
            <div class="space-y-4" wire:loading.class="opacity-50">
                @forelse($products as $product)
                    <div
                        class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-gray-300 transition-colors shadow-sm hover:shadow-md">
                        <div class="flex flex-col sm:flex-row">
                            <!-- Product Image -->
                            <div class="relative group sm:w-64 w-full">
                                @if ($product->firstVariantImage)
                                    <a href="{{ route('public.product.view', $product->slug) }}">
                                        <img src="{{ $product->firstVariantImage->image_path ?? asset('images/placeholder.jpg') }}"
                                            alt="{{ $product->name }}" class="w-full h-48 sm:h-64 object-cover">
                                    </a>
                                @else
                                    <div class="w-full h-48 sm:h-64 bg-gray-100 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Action Icons (Always visible now) -->
                                <div class="absolute top-2 right-2 flex flex-col space-y-2">
                                    <!-- View Product -->
                                    <a href="{{ route('public.product.view', $product->slug) }}"
                                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors shadow-md"
                                        title="View Product">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Wishlist -->
                                    @php
                                        $isInWishlist = in_array($product->id, $wishlistItems ?? []);
                                    @endphp
                                    <button
                                        wire:click="{{ $isInWishlist ? 'removeFromWishlist' : 'addToWishlist' }}({{ $product->id }})"
                                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors shadow-md"
                                        title="{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                        <svg class="w-5 h-5 {{ $isInWishlist ? 'text-red-500 fill-current' : 'text-gray-600' }} transition-colors"
                                            fill="{{ $isInWishlist ? 'currentColor' : 'none' }}"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Discount Badge -->
                                @if ($product->discount_price && $product->discount_price < $product->price)
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-[#8f4da7] text-white text-xs font-bold px-2 py-1 rounded">
                                            {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                            OFF
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 p-6">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <a wire:navigate href="{{ route('public.product.view', $product->slug) }}"
                                            class="text-lg font-semibold text-gray-900 mb-1 hover:text-[#8f4da7] transition-colors">{{ $product->name }}</a>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ $product->category->title ?? 'No Category' }}</p>

                                        <!-- Price -->
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="text-2xl font-bold text-[#8f4da7]">
                                                ₹{{ number_format($product->discount_price ?? $product->price) }}
                                            </span>
                                            @if ($product->discount_price && $product->discount_price < $product->price)
                                                <span class="text-lg text-gray-500 line-through">
                                                    ₹{{ number_format($product->price) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Product Meta -->
                                        <div class="text-sm text-gray-500 space-y-1 mb-4">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the
                                                majority have suffered alteration in some form, by injected humour, or
                                                randomised words which don't look even slightly believable.</p>
                                        </div>

                                        <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-4">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                @if ($product->IsInStock())
                                                    <span class="text-green-600">In Stock</span>
                                                @else
                                                    <span class="text-red-600">Out of Stock</span>
                                                @endif
                                            </span>
                                            @if ($product->is_customizable)
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Customizable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-3-3V6a3 3 0 10-6 0v3M4 20h16a2 2 0 002-2V10a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-500 mb-4">Try adjusting your search criteria or filters</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>