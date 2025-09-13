<div x-data="productView()" class="min-h-screen bg-gray-50 p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>
                <p class="mt-2 text-gray-600">
                    @if($product->category)
                        <a href="{{ route('public.category.show', $product->category->slug) }}" class="hover:text-blue-600">
                            {{ $product->category->title }}
                        </a>
                    @else
                        <span>Not assigned to a category</span>
                    @endif
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('public.products.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 xl:grid-cols-4">
            <!-- Left Column - Product Images -->
            <div class="xl:col-span-1">
                <div class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-medium text-gray-900">Product Images</h2>
                    
                    <!-- Main Image -->
                    <div class="mb-6 relative" x-data="{ isLoading: false }">
                        <img id="mainProductImage" 
                               alt="{{ $product->name }}" 
                             class="h-64 w-full rounded-lg object-cover border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-zoom-in"
                             @click="openLightbox($event.target.src)"
                             x-bind:class="{ 'opacity-50': isLoading }">
                        <div x-show="isLoading" class="absolute inset-0 bg-gray-200 rounded-lg flex items-center justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        </div>
                        @if($variantImage)
                            <div class="absolute top-2 left-2">
                                <span class="rounded bg-blue-600 px-2 py-1 text-xs font-medium text-white shadow-sm">
                                    Featured
                                </span>
                            </div>  
                        @endif
                    </div>

                    <!-- Gallery Images -->
                    @if(!empty($formattedProductImages))
                        <div>
                            <h3 class="mb-3 text-sm font-medium text-gray-700">Gallery Images ({{ count($formattedProductImages) }})</h3>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($formattedProductImages as $galleryImage)
                                    <div class="relative group">
                                        <img src="{{ $galleryImage }}" 
                                             alt="{{ $product->name }} gallery" 
                                             class="h-20 w-full rounded object-cover border border-gray-200 hover:border-blue-300 transition-all cursor-pointer"
                                             @click="updateMainImage(this.src)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm font-medium">No images uploaded</p>
                            <p class="text-xs text-gray-400">Images will be displayed here once uploaded</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Columns - Product Information -->
            <div class="xl:col-span-3 space-y-6">
                <!-- Basic Information -->
                <div class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Basic Information
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Product Name</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $product->name }}</dd>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($product->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->title }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Not assigned</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Brand</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($product->brand)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $product->brand->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Not assigned</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">SKU</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-900 bg-white px-2 py-1 rounded border">{{ $sku }}</dd>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Stock Quantity</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $hasStock ? ($currentVariant && $currentVariant->stock > 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') : 'bg-red-100 text-red-800' }}">
                                    {{ $currentVariant ? $currentVariant->stock : ($product->quantity ?? 0) }} units
                                </span>
                            </dd>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-900 bg-white px-2 py-1 rounded border">{{ $product->slug }}</dd>
                        </div>
                    </div>

                    @if($product->description)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-700 mb-2">Description</dt>
                            <dd class="text-sm text-gray-900 bg-gray-50 p-4 rounded-lg border border-gray-100 prose">{!! nl2br(e($product->description)) !!}</dd>
                        </div>
                    @endif
                </div>

                <!-- Pricing -->
                <div class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Pricing Information
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <dt class="text-sm font-medium text-blue-700">Regular Price (MRP)</dt>
                            <dd class="mt-1 text-2xl font-bold text-blue-900">₹{{ number_format($regularPrice, 2) }}</dd>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <dt class="text-sm font-medium text-green-700">Selling Price</dt>
                            <dd class="mt-1 text-2xl font-bold text-green-900">₹{{ number_format($price, 2) }}</dd>
                        </div>
                        
                        @if($hasDiscount)
                            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                <dt class="text-sm font-medium text-red-700">You Save</dt>
                                <dd class="mt-1 text-2xl font-bold text-red-900">
                                    ₹{{ number_format($savingAmount, 2) }}
                                    <span class="text-sm font-normal">({{ $savingPercentage }}% off)</span>
                                </dd>
                            </div>
                        @endif
                    </div>

                    @if($deliveryCharge > 0)
                        <div class="mt-4 text-sm text-gray-600">
                            + ₹{{ number_format($deliveryCharge, 2) }} delivery charge
                        </div>
                    @endif
                </div>

                <!-- Variant Selection -->
                @if(!empty($availableVariants))
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Select Variants
                        </h2>
                        
                        <div class="space-y-4">
                            @foreach($availableVariants as $type => $options)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 capitalize">{{ $type }}</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($options as $option)
                                            <button 
                                                wire:click="selectVariant('{{ $type }}', '{{ $option }}')"
                                                class="px-3 py-2 text-sm font-medium rounded-full border transition-all duration-200
                                                    @if(isset($selectedVariants[$type]) && $selectedVariants[$type] === $option)
                                                        bg-blue-600 text-white border-blue-600
                                                    @elseif(isset($disabledVariants[$type][$option]) && $disabledVariants[$type][$option])
                                                        bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed
                                                    @else
                                                        bg-white text-gray-700 border-gray-300 hover:border-blue-300 hover:bg-blue-50
                                                    @endif"
                                                @if(isset($disabledVariants[$type][$option]) && $disabledVariants[$type][$option]) disabled @endif>
                                                {{ $option }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quantity Selector -->
                @if($hasStock)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            Quantity
                        </h2>
                        <div class="flex items-center space-x-2">
                            <button wire:click="$set('quantity', {{ $quantity - 1 }})" 
                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50"
                                    :disabled="{{ $quantity <= 1 }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                            </button>
                            <span class="w-8 text-center text-sm font-medium">{{ $quantity }}</span>
                            <button wire:click="$set('quantity', {{ $quantity + 1 }})" 
                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if($hasStock)
                            <button wire:click="addToCart({{ $product->id }})"
                                    class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M16 20a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                <span>Add to Cart</span>
                            </button>
                            <button wire:click="buyNow()"
                                    class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M16 20a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                <span>Buy Now</span>
                            </button>
                        @else
                            <button disabled class="flex-1 bg-gray-400 text-white py-3 px-6 rounded-lg font-medium cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif

                        <a href="{{ $this->getCustomizationWhatsappUrl($product->name) }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="flex-1 bg-green-500 text-white py-3 px-6 rounded-lg font-medium hover:bg-green-600 transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>

                <!-- Features & Status -->
                <div class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Features & Status
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex justify-center mb-2">
                                @if($product->status)
                                    <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <dt class="text-sm font-medium text-gray-700">Status</dt>
                            <dd class="text-sm {{ $product->status ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </dd>
                        </div>
                        
                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex justify-center mb-2">
                                @if($product->is_customizable)
                                    <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a1 1 0 01-1-1V9a1 1 0 011-1h1a2 2 0 100-4H4a1 1 0 01-1-1V4a1 1 0 011-1h3a1 1 0 001-1v1z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <dt class="text-sm font-medium text-gray-700">Customizable</dt>
                            <dd class="text-sm {{ $product->is_customizable ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ $product->is_customizable ? 'Yes' : 'No' }}
                            </dd>
                        </div>
                        
                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex justify-center mb-2">
                                @if($product->featured)
                                    <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <dt class="text-sm font-medium text-gray-700">Featured</dt>
                            <dd class="text-sm {{ $product->featured ? 'text-yellow-600' : 'text-gray-500' }}">
                                {{ $product->featured ? 'Yes' : 'No' }}
                            </dd>
                        </div>

                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex justify-center mb-2">
                                <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 12v3m0-3h8m-8 0H6a2 2 0 01-2-2V9a2 2 0 012-2h12a2 2 0 012 2v7a2 2 0 01-2 2h-2"/>
                                    </svg>
                                </div>
                            </div>
                            <dt class="text-sm font-medium text-gray-700">Created</dt>
                            <dd class="text-sm text-gray-600">{{ $product->created_at->format('M d, Y') }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Product Variants -->
                @if($product->variants->count() > 0)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Product Variants 
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $product->variants->count() }} variants
                            </span>
                        </h2>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant Details</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($product->variants as $variant)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($variant->primary_image)
                                                    <img src="{{ $variant->primary_image }}" 
                                                         alt="{{ $variant->variant_type }}" 
                                                         class="h-12 w-12 rounded-lg object-cover border border-gray-200">
                                                @else
                                                    <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $variant->variant_type }}</div>
                                                <div class="text-sm text-gray-500">
                                                    @foreach($this->parseVariantValues($variant->variant_values) as $type => $value)
                                                        {{ $type }}: {{ $value }}<br>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <span class="font-medium text-green-600">₹{{ number_format($variant->price ?? $product->price, 2) }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $variant->stock > 10 ? 'bg-green-100 text-green-800' : 
                                                       ($variant->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $variant->stock }} units
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-mono text-gray-900">
                                                {{ $variant->sku ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Variants Summary -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <dt class="text-sm font-medium text-blue-700">Total Variants</dt>
                                <dd class="mt-1 text-2xl font-semibold text-blue-900">{{ $product->variants->count() }}</dd>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <dt class="text-sm font-medium text-green-700">Total Stock</dt>
                                <dd class="mt-1 text-2xl font-semibold text-green-900">{{ $product->variants->sum('stock') }} units</dd>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                <dt class="text-sm font-medium text-purple-700">Price Range</dt>
                                <dd class="mt-1 text-lg font-semibold text-purple-900">
                                    ₹{{ number_format($product->variants->min('price') ?? $product->price, 2) }} - 
                                    ₹{{ number_format($product->variants->max('price') ?? $product->price, 2) }}
                                </dd>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Product Highlights -->
                @if($product->highlights->count() > 0)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Product Highlights
                        </h2>
                        
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($product->highlights as $highlight)
                                <li class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $highlight->highlights }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- SEO Information -->
                @if($product->meta_title || $product->meta_description)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            SEO Information
                        </h2>
                        
                        @if($product->meta_title)
                            <div class="mb-4">
                                <dt class="text-sm font-medium text-gray-700 mb-2">Meta Title</dt>
                                <dd class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $product->meta_title }}</dd>
                            </div>
                        @endif
                        
                        @if($product->meta_description)
                            <div>
                                <dt class="text-sm font-medium text-gray-700 mb-2">Meta Description</dt>
                                <dd class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $product->meta_description }}</dd>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Related Products -->
                @if($relatedProducts->count() > 0)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m-10 10h10m-7-7v10m7-13v10"/>
                            </svg>
                            Related Products
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($relatedProducts as $related)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow">
                                    <div class="relative h-48">
                                        <img src="{{ $related->images->first()?->image_path ?? asset('images/placeholder.jpg') }}" 
                                             alt="{{ $related->name }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $related->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1 truncate">{{ $related->formatted_discount_price ?? $related->formatted_price }}</p>
                                        <a href="{{ route('public.product.view', $related->slug) }}" 
                                           class="mt-2 inline-block text-blue-600 text-sm hover:underline">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div x-show="showLightbox" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" 
             @click.away="showLightbox = false">
            <div class="relative max-w-4xl max-h-full">
                <img x-ref="lightboxImage" :src="lightboxImageSrc" alt="Product Image" class="max-w-full max-h-full object-contain rounded-lg">
                <button @click="showLightbox = false" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">&times;</button>
            </div>
        </div>
    </div>

    <script>
        function productView() {
            return {
                showLightbox: false,
                lightboxImageSrc: '',
                isLoading: false,

                updateMainImage(src) {
                    this.isLoading = true;
                    const mainImg = document.getElementById('mainProductImage');
                    mainImg.src = src;
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 300);
                },

                openLightbox(src) {
                    this.lightboxImageSrc = src;
                    this.showLightbox = true;
                },

                init() {
                    // Listen for variant updates from Livewire
                    this.$wire.on('variantUpdated', (event) => {
                        if (event.detail.image) {
                            this.updateMainImage(event.detail.image);
                        }
                        // Note: Gallery updates are handled via Livewire re-render
                    });
                }
            }
        }

        // Handle notifications
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                // Replace with your preferred toast library (e.g., Toastify, SweetAlert)
                alert(`${event.detail.message} (${event.detail.type})`);
            });
        });
    </script>

    <style>
        .prose {
            @apply text-gray-700 leading-relaxed;
        }
        .prose p {
            @apply mb-3;
        }
    </style>
</div>