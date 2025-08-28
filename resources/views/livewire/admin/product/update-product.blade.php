<div class="min-h-screen bg-gray-50 p-4 lg:p-8">
    <div class="mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-8 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Product</h1>
                <p class="mt-2 text-gray-600">Update {{ $product->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </div>

        <form wire:submit="update" class="space-y-8">
            <!-- Basic Information -->
            <div class="rounded-lg bg-white p-6">
                <h2 class="mb-6 text-lg font-medium text-gray-900">Basic Information</h2>
                
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Product Name -->
                    <div class="lg:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                        <input type="text" 
                               wire:model="name" 
                               id="name"
                               class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Enter product name">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select wire:model="category_id" 
                                id="category_id"
                                class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select wire:model="brand_id" 
                                id="brand_id"
                                class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                        <div class="flex rounded-lg">
                            <input type="text" 
                                   wire:model="sku" 
                                   id="sku"
                                   class="flex-1 p-2 rounded-l-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Product SKU">
                            <button type="button" 
                                    wire:click="generateSKU"
                                    class="rounded-r-lg border border-l-0 border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-500 hover:bg-gray-100">
                                Generate
                            </button>
                        </div>
                        @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                        <input type="number" 
                               wire:model="quantity" 
                               id="quantity"
                               min="0"
                               class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="0">
                        @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea wire:model="description" 
                                  id="description"
                                  rows="4"
                                  class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Describe the product features, materials, customization options..."></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="rounded-lg bg-white p-6">
                <h2 class="mb-6 text-lg font-medium text-gray-900">Pricing</h2>
                
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Regular Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Original Price (₹) *</label>
                        <input type="number" 
                               wire:model="price" 
                               id="price"
                               step="0.01"
                               min="0"
                               class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="0.00">
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Discount Price -->
                    <div>
                        <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-2">Selling Price (₹) *</label>
                        <input type="number" 
                               wire:model="discount_price" 
                               id="discount_price"
                               step="0.01"
                               min="0"
                               class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="0.00">
                        @error('discount_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if($price && $discount_price && $discount_price < $price)
                            <p class="mt-1 text-sm text-green-600">
                                {{ number_format((($price - $discount_price) / $price) * 100, 1) }}% discount
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customization Options -->
            <div class="rounded-lg bg-white p-6">
                <h2 class="mb-6 text-lg font-medium text-gray-900">Customization Options</h2>
                
                <!-- Is Customizable Toggle -->
                <div class="flex items-center mb-6">
                    <button type="button"
                            wire:click="$toggle('is_customizable')"
                            class="relative inline-flex w-11 h-6 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $is_customizable ? 'bg-blue-600' : 'bg-gray-200' }}"
                            role="switch" 
                            aria-checked="{{ $is_customizable ? 'true' : 'false' }}"
                            id="is_customizable">
                        <span class="sr-only">Toggle custom printing support</span>
                        <span aria-hidden="true" 
                              class="w-5 h-5 pointer-events-none inline-block rounded-full bg-white shadow transform ring-0 transition duration-200 ease-in-out {{ $is_customizable ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                    <label for="is_customizable" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                        This product supports custom printing
                    </label>
                </div>
            </div>

            <!-- Product Highlights -->
            <div class="rounded-lg bg-white p-6">
                <h2 class="mb-6 text-lg font-medium text-gray-900">Product Highlights</h2>
                
                <div class="space-y-2">
                    @foreach($highlights as $index => $highlight)
                        <div class="flex items-center space-x-2">
                            <span class="flex-1  rounded bg-gray-100 px-3 py-2 text-sm">{{ $highlight }}</span>
                            <button type="button" 
                                    wire:click="removeHighlight({{ $index }})"
                                    class="text-red-600 hover:text-red-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                    
                    <div class="flex space-x-2">
                        <input type="text" 
                               wire:model="new_highlight"
                               wire:keydown.enter="addHighlight"
                               class="flex-1 p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g., High-quality cotton material">
                        <button type="button" 
                                wire:click="addHighlight"
                                class="rounded-lg bg-blue-100 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-200">
                            Add
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="rounded-lg bg-white p-6">
                <h2 class="mb-6 text-lg font-medium text-gray-900">Product Images</h2>
                
                <!-- Existing Images -->
                @if($existing_images)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Current Images</h3>
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            @foreach($existing_images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image['image_path']) }}" 
                                         alt="Product Image" 
                                         class="h-32 w-full rounded-lg object-cover">
                                    
                                    <!-- Primary Image Badge -->
                                    @if($image['id'] == $primary_image_id)
                                        <div class="absolute top-2 left-2">
                                            <span class="rounded bg-blue-600 px-2 py-1 text-xs font-medium text-white">
                                                Primary
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <!-- Actions -->
                                    <div class="absolute inset-0 flex items-center justify-center space-x-2 bg-black bg-opacity-50 opacity-0 transition-opacity group-hover:opacity-100 rounded-lg">
                                        @if($image['id'] != $primary_image_id)
                                            <button type="button" 
                                                    wire:click="setPrimaryImage({{ $image['id'] }})"
                                                    class="rounded bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-100">
                                                Set Primary
                                            </button>
                                        @endif
                                        <button type="button" 
                                                wire:click="removeExistingImage({{ $image['id'] }})"
                                                class="rounded bg-red-600 px-2 py-1 text-xs font-medium text-white hover:bg-red-700">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add New Images -->
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Add New Images</h3>
                    <input type="file" 
                           wire:model="new_images" 
                           multiple 
                           accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('new_images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                @if($new_images)
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        @foreach($new_images as $index => $image)
                            <div class="relative group">
                                <img src="{{ $image->temporaryUrl() }}" 
                                     alt="New Image Preview" 
                                     class="h-32 w-full rounded-lg object-cover">
                                
                                <!-- Actions -->
                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 transition-opacity group-hover:opacity-100 rounded-lg">
                                    <button type="button" 
                                            wire:click="removeNewImage({{ $index }})"
                                            class="rounded bg-red-600 px-2 py-1 text-xs font-medium text-white hover:bg-red-700">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- SEO & Settings -->
            <div class="rounded-lg bg-white p-6">
                <h2 class="mb-6 text-lg font-medium text-gray-900">SEO & Settings</h2>
                
                <div class="space-y-6">
                    <!-- Meta Title -->
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" 
                               wire:model="meta_title" 
                               id="meta_title"
                               class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="SEO title for search engines">
                        @error('meta_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea wire:model="meta_description" 
                                  id="meta_description"
                                  rows="3"
                                  class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="SEO description for search engines"></textarea>
                        @error('meta_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status Toggles -->
                    <div class="flex flex-col space-y-4 sm:flex-row sm:space-x-8 sm:space-y-0">
                        <div class="flex items-center">
                            <button type="button"
                                    wire:click="$toggle('status')"
                                    class="group relative inline-flex w-11 h-6 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $status ? 'bg-green-500 focus:ring-green-500' : 'bg-gray-300 hover:bg-gray-400 focus:ring-gray-400' }}"
                                    role="switch" 
                                    aria-checked="{{ $status ? 'true' : 'false' }}"
                                    id="status">
                                <span class="sr-only">Toggle product status</span>
                                <span aria-hidden="true" 
                                      class="w-5 h-5 pointer-events-none inline-block rounded-full bg-white shadow-lg transform ring-0 transition-all duration-200 ease-in-out group-hover:shadow-xl {{ $status ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            <label for="status" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer select-none">
                                Active (visible to customers)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <button type="button"
                                    wire:click="$toggle('featured')"
                                    class="group relative inline-flex w-11 h-6 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $featured ? 'bg-yellow-400 focus:ring-yellow-500' : 'bg-gray-300 hover:bg-gray-400 focus:ring-gray-400' }}"
                                    role="switch" 
                                    aria-checked="{{ $featured ? 'true' : 'false' }}"
                                    id="featured">
                                <span class="sr-only">Toggle featured status</span>
                                <span aria-hidden="true" 
                                      class="w-5 h-5 pointer-events-none inline-block rounded-full bg-white shadow-lg transform ring-0 transition-all duration-200 ease-in-out group-hover:shadow-xl {{ $featured ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            <label for="featured" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer select-none">
                                Featured product
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                    <span wire:loading.remove wire:target="update">Update Product</span>
                    <span wire:loading wire:target="update">Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
