<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" 
     x-data="{ mobileFiltersOpen: false }" 
     wire:ignore.self
     @close-mobile-filters.window="if (window.innerWidth < 1024) { mobileFiltersOpen = false }">
    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Left Sidebar - Filters -->
        <div class="w-full lg:w-1/4">
            <!-- Mobile Filter Toggle -->
            <div class="lg:hidden mb-4">
                <button 
                    @click="mobileFiltersOpen = !mobileFiltersOpen"
                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span x-text="mobileFiltersOpen ? 'Hide Filters' : 'Show Filters'"></span>
                    <svg class="w-4 h-4 ml-2 transition-transform" :class="{'rotate-180': mobileFiltersOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <!-- Filters Container -->
            <div class="bg-white rounded-lg border border-gray-200" 
                 :class="{'hidden lg:block': !mobileFiltersOpen, 'block': mobileFiltersOpen}">
                
                <!-- Search Bar -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Search</h6>
                    <div class="relative">
                        <input 
                            type="text" 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Search products..."
                            wire:model.live.debounce.300ms="searchQuery"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Product Categories -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Product categories</h6>
                    
                    <!-- All Categories Option -->
                    <div class="mb-2">
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="radio" 
                                name="temp_category"
                                value=""
                                wire:model.live="tempSelectedCategory"
                                class="text-purple-600 focus:ring-purple-500"
                            >
                            <span class="ml-2 text-sm text-gray-700">All Categories</span>
                        </label>
                    </div>
                    
                    <!-- Parent Categories -->
                    @php
                        $categoriesToShow = $showAllCategories ? $parentCategories : $parentCategories->take(8);
                    @endphp
                    
                    @foreach($categoriesToShow as $category)
                        <div class="mb-2">
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="temp_category"
                                    value="{{ $category->id }}"
                                    wire:model.live="tempSelectedCategory"
                                    class="text-purple-600 focus:ring-purple-500"
                                >
                                <span class="ml-2 text-sm text-gray-700">{{ $category->title }}</span>
                            </label>
                            
                            <!-- Subcategories -->
                            @if($tempSelectedCategory == $category->id && count($subcategories) > 0)
                                <div class="ml-6 mt-2 space-y-1">
                                    @foreach($subcategories as $subcategory)
                                        <label class="flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                wire:click="toggleTempSubcategory({{ $subcategory->id }})"
                                                @if(in_array($subcategory->id, $tempSelectedSubcategories)) checked @endif
                                                class="text-purple-600 focus:ring-purple-500 rounded"
                                            >
                                            <span class="ml-2 text-xs text-gray-600">{{ $subcategory->title }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                    
                    <!-- See More/Less for Categories -->
                    @if($parentCategories->count() > 8)
                        <button 
                            wire:click="toggleShowAllCategories"
                            class="text-purple-600 text-sm font-medium hover:text-purple-700 mt-2"
                        >
                            {{ $showAllCategories ? 'See Less' : 'See More (' . ($parentCategories->count() - 8) . ' more)' }}
                        </button>
                    @endif
                </div>

                <!-- Brand Filter -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Brands</h6>
                    
                    @php
                        $brandsToShow = $showAllBrands ? $brands : $brands->take(8);
                    @endphp
                    
                    <div class="space-y-2">
                        @foreach($brandsToShow as $brand)
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    wire:click="toggleTempBrand({{ $brand->id }})"
                                    @if(in_array($brand->id, $tempSelectedBrands)) checked @endif
                                    class="text-purple-600 focus:ring-purple-500 rounded"
                                >
                                <span class="ml-2 text-sm text-gray-700">{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    
                    <!-- See More/Less for Brands -->
                    @if($brands->count() > 8)
                        <button 
                            wire:click="toggleShowAllBrands"
                            class="text-purple-600 text-sm font-medium hover:text-purple-700 mt-2"
                        >
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
                            <div x-ref="progress" class="absolute h-2 bg-purple-500 rounded-full"></div>
                        </div>
                        
                        <!-- Range Inputs -->
                        <div class="relative">
                            <input 
                                type="range" 
                                :min="minRange" 
                                :max="maxRange" 
                                x-model="tempMinPrice"
                                class="absolute w-full h-2 bg-transparent appearance-none pointer-events-none [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-purple-500 [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:cursor-pointer [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-purple-500 [&::-moz-range-thumb]:border-none [&::-moz-range-thumb]:cursor-pointer"
                            >
                            <input 
                                type="range" 
                                :min="minRange" 
                                :max="maxRange" 
                                x-model="tempMaxPrice"
                                class="absolute w-full h-2 bg-transparent appearance-none pointer-events-none [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-purple-500 [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:cursor-pointer [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-purple-500 [&::-moz-range-thumb]:border-none [&::-moz-range-thumb]:cursor-pointer"
                            >
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
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableColors as $colorName => $colorCode)
                            <div 
                                class="w-8 h-8 rounded-full cursor-pointer border-2 {{ in_array($colorName, $tempSelectedColors) ? 'border-purple-500' : 'border-gray-200' }} relative flex items-center justify-center"
                                style="background-color: {{ $colorCode }};"
                                wire:click="toggleTempColor('{{ $colorName }}')"
                                title="{{ ucfirst($colorName) }}"
                            >
                                @if(in_array($colorName, $tempSelectedColors))
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Size Filter -->
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Filter by Size</h6>
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableSizes as $size)
                            <button 
                                type="button"
                                class="px-3 py-1 text-sm border rounded-md transition-colors {{ in_array($size, $tempSelectedSizes) ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
                                wire:click="toggleTempSize('{{ $size }}')"
                            >
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Rating Filter -->
                <div class="p-4">
                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Sort by Rating</h6>
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center mb-2 cursor-pointer hover:bg-gray-50 p-1 rounded" wire:click="setTempRating({{ $i }})">
                            <div class="flex items-center mr-2">
                                @for($j = 1; $j <= 5; $j++)
                                    <svg class="w-4 h-4 {{ $j <= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm {{ $tempMinimumRating == $i ? 'text-purple-600 font-medium' : 'text-gray-600' }}">
                                & Up
                            </span>
                        </div>
                    @endfor
                </div>

                <!-- Apply All Filters Button -->
                <div class="p-4 border-t border-gray-200" 
                     x-data="{ 
                        applyFilters() {
                            $wire.applyAllFilters();
                            if (window.innerWidth < 1024) {
                                mobileFiltersOpen = false;
                            }
                        }
                     }">
                    <button 
                        @click="applyFilters()"
                        class="w-full bg-purple-600 text-white py-3 px-4 rounded-md text-sm font-medium hover:bg-purple-700 transition-colors mb-2"
                    >
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Apply All Filters
                    </button>
                </div>

                <!-- Clear Filters -->
                @if($searchQuery || !empty($selectedSubcategories) || !empty($selectedBrands) || ($minPrice > 0 || $maxPrice < 10000) || !empty($selectedColors) || !empty($selectedSizes) || $minimumRating > 0 || $selectedCategory)
                    <div class="p-4 border-t border-gray-200">
                        <button class="w-full bg-red-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-red-700 transition-colors" wire:click="resetFilters">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear All Filters
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Content - Products -->
        <div class="flex-1">
            <!-- Results Header & Sort -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
                    </h2>
                </div>
                
                <!-- Sort Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button 
                        @click="open = !open"
                        class="flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        @switch($sortBy)
                            @case('price_low') Default sorting @break
                            @case('price_high') Sort by price: high to low @break
                            @case('name') Sort by name @break
                            @case('discount') Sort by discount @break
                            @case('latest') Sort by latest @break
                            @default Default sorting
                        @endswitch
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" wire:click="$set('sortBy', 'popularity')" @click="open = false">Sort by popularity</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" wire:click="$set('sortBy', 'latest')" @click="open = false">Sort by latest</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" wire:click="$set('sortBy', 'price_low')" @click="open = false">Sort by price: low to high</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" wire:click="$set('sortBy', 'price_high')" @click="open = false">Sort by price: high to low</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" wire:click="$set('sortBy', 'name')" @click="open = false">Sort by name</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List (Row Layout) -->
            <div class="space-y-4" wire:loading.class="opacity-50">
                @forelse($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-gray-300 transition-colors">
                        <div class="flex flex-col sm:flex-row">
                            <!-- Product Image -->
                            <div class="relative group sm:w-64 w-full">
                                @if($product->images->first())
                                    <img 
                                        src="{{ $product->images->first()->image_path }}" 
                                        alt="{{ $product->name }}"
                                        class="w-full h-48 sm:h-64 object-cover"
                                    >
                                @else
                                    <div class="w-full h-48 sm:h-64 bg-gray-100 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Hover Icons (Hidden on Mobile) -->
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 hidden sm:flex flex-col space-y-2">
                                    <!-- View Product -->
                                    <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Wishlist -->
                                    <button 
                                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors"
                                        title="Add to Wishlist"
                                    >
                                        <svg class="w-5 h-5 text-gray-600 hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Discount Badge -->
                                @if($product->discount_price && $product->discount_price < $product->price)
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1 p-6">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ $product->category->title ?? 'No Category' }}</p>
                                        
                                        <!-- Price -->
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="text-2xl font-bold text-purple-600">
                                                ₹{{ number_format($product->discount_price ?? $product->price) }}
                                            </span>
                                            @if($product->discount_price && $product->discount_price < $product->price)
                                                <span class="text-lg text-gray-500 line-through">
                                                    ₹{{ number_format($product->price) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Product Meta -->
                                        <div class="text-sm text-gray-500 space-y-1 mb-4">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
                                        </div>

                                        <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-4">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                {{ $product->sku ?? 'N/A' }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                                @if($product->quantity > 0)
                                                    <span class="text-green-600">In Stock</span>
                                                @else
                                                    <span class="text-red-600">Out of Stock</span>
                                                @endif
                                            </span>
                                            @if($product->is_customizable)
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Customizable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Button -->
                                <div class="flex justify-end">
                                    <button 
                                        class="bg-purple-600 text-white px-6 py-2 rounded-md font-medium hover:bg-purple-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                        @if($product->quantity <= 0) disabled @endif
                                        title="Add to Cart"
                                    >
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        @if($product->is_customizable)
                                            Personalize
                                        @else
                                            Add to Cart
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-3-3V6a3 3 0 10-6 0v3M4 20h16a2 2 0 002-2V10a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-500 mb-4">Try adjusting your search criteria or filters</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>