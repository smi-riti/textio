<div class="min-h-screen bg-gray-50">
    <!-- Stepper -->
    <livewire:admin.product.product-stepper :currentStep="$currentStep" :completedSteps="$completedSteps" />
    
    <div class="p-4 lg:p-8">
        <div class="mx-auto max-w-4xl">
            <!-- Header -->
            <div class="mb-8 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Create Product</h1>
                    <p class="mt-2 text-gray-600">Add a new custom printing product to your catalog</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.index') }}" 
                       class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Products
                    </a>
                </div>
            </div>

            <!-- Loading Overlay -->
            @if($isSaving)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 shadow-lg max-w-md mx-auto">
                    <div class="flex items-center">
                        <svg class="animate-spin h-5 w-5 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-700">{{ $loadingMessage }}</span>
                    </div>
                </div>
            </div>
            @endif

            <form wire:submit="save" class="space-y-8">
                <!-- Step 1: Basic Information -->
                @if($currentStep === 1)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900">Basic Information</h2>
                        
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <!-- Product Name -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                                <input type="text" wire:model.live="name" 
                                       placeholder="Enter product name"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Slug -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                                <input type="text" wire:model="slug" 
                                       placeholder="product-slug"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                <p class="mt-1 text-sm text-gray-500">This will be used in the product URL</p>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select wire:model="category_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Brand -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                <select wire:model="brand_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- SKU -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                                <div class="flex">
                                    <input type="text" wire:model="sku" 
                                           class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="button" wire:click="generateSKU"
                                            class="px-4 py-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-200 text-sm font-medium">
                                        Generate
                                    </button>
                                </div>
                                @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                                <input type="number" wire:model="quantity" 
                                       placeholder="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea wire:model="description" rows="4" 
                                          placeholder="Describe your product..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Customization Options -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-md font-medium text-gray-900 mb-4">Customization Options</h3>
                            
                            <!-- Is Customizable Toggle -->
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_customizable" id="is_customizable"
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <label for="is_customizable" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                                    This product can be customized
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step 2: Pricing -->
                @if($currentStep === 2)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900">Pricing</h2>
                        
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <!-- Regular Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price (MRP) *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                    <input type="number" step="0.01" wire:model.live="price" 
                                           placeholder="0.00"
                                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Discount Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Selling Price *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                    <input type="number" step="0.01" wire:model.live="discount_price" 
                                           placeholder="0.00"
                                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">This should be less than regular price</p>
                                @error('discount_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if($price && $discount_price && $price > 0)
                            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-green-800">
                                        Discount: {{ number_format((($price - $discount_price) / $price) * 100, 2) }}% 
                                        (Save ₹{{ number_format($price - $discount_price, 2) }})
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Step 3: Images -->
                @if($currentStep === 3)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-medium text-gray-900">Product Images</h2>
                        
                        <!-- Uploading Indicators -->
                        @if($isUploadingFeaturedImage || $isUploadingGalleryImages)
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            @if($isUploadingFeaturedImage)
                            <div class="flex items-center text-blue-700 text-sm">
                                <svg class="animate-spin h-4 w-4 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading featured image...
                            </div>
                            @endif
                            
                            @if($isUploadingGalleryImages)
                            <div class="flex items-center text-blue-700 text-sm mt-2">
                                <svg class="animate-spin h-4 w-4 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading gallery images...
                            </div>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Featured Image -->
                        <div class="mb-8">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Featured Image *</h3>
                            <p class="text-sm text-gray-500 mb-3">Upload a primary/featured image for your product</p>
                            
                            <div class="mb-4">
                                <input type="file" wire:model="featured_image" accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('featured_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            @if($featured_image_preview)
                                <div class="relative inline-block">
                                    <img src="{{ $featured_image_preview }}" 
                                         class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                                    <button type="button" wire:click="removeFeaturedImage"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                                        ×
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Gallery Images -->
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Gallery Images</h3>
                            <p class="text-sm text-gray-500 mb-3">Upload additional images for your product gallery</p>
                            
                            <input type="file" wire:model="gallery_images" multiple accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            @error('gallery_images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        @if($gallery_images_preview)
                            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                                @foreach($gallery_images_preview as $index => $image)
                                    <div class="relative">
                                        <img src="{{ $image }}" 
                                             class="w-full h-24 object-cover rounded-lg border border-gray-300">
                                        <button type="button" wire:click="removeGalleryImage({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Step 4: Variants -->
                @if($currentStep === 4)
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <livewire:admin.product.product-variants :variantCombinations="$variantCombinations" :product="null" :isEdit="false" />
                    </div>
                @endif

                <!-- Step 5: Product Highlights and SEO -->
                @if($currentStep === 5)
                    <div class="space-y-6">
                        <!-- Product Highlights -->
                        <div class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="mb-6 text-lg font-medium text-gray-900">Product Highlights</h2>
                            
                            <div class="space-y-2">
                                @foreach($highlights as $index => $highlight)
                                    <div class="flex items-center space-x-2">
                                        <input type="text" value="{{ $highlight }}" readonly
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                        <button type="button" wire:click="removeHighlight({{ $index }})"
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                                
                                <div class="flex space-x-2">
                                    <input type="text" wire:model="new_highlight" 
                                           placeholder="Add a product highlight..."
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="button" wire:click="addHighlight"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- SEO & Settings -->
                        <div class="rounded-lg bg-white p-6 shadow-sm">
                            <h2 class="mb-6 text-lg font-medium text-gray-900">SEO & Settings</h2>
                            
                            <div class="space-y-6">
                                <!-- Meta Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                    <input type="text" wire:model="meta_title" 
                                           placeholder="SEO title for search engines"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('meta_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Meta Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                    <textarea wire:model="meta_description" rows="3"
                                              placeholder="SEO description for search engines"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                    @error('meta_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Status Toggles -->
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="status" id="status"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                        <label for="status" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                                            Product is active and visible to customers
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="featured" id="featured"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                        <label for="featured" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                                            Feature this product on homepage
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step Navigation and Submit -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    @if($currentStep > 1)
                        <button type="button" wire:click="previousStep" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Previous
                        </button>
                    @else
                        <div></div>
                    @endif

                    @if($currentStep < 5)
                        <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                                class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="nextStep">Next Step</span>
                            <span wire:loading wire:target="nextStep">Processing...</span>
                            <svg wire:loading.remove wire:target="nextStep" class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <svg wire:loading wire:target="nextStep" class="animate-spin w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    @else
                        <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="save">Create Product</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>