<div class="min-h-screen bg-gray-50 p-4 lg:p-8">
    <div class="mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-8 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Product Variant</h1>
                <p class="mt-2 text-gray-600">Update variant for {{ $product->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.variants', $product) }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Variants
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Current Variant Info -->
        <div class="mb-6 rounded-lg bg-white p-6 shadow-sm border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Current Variant</h2>
            <div class="flex flex-wrap gap-2">
                @if($variant->variant_values && is_array($variant->variant_values))
                    @foreach($variant->variant_values as $key => $value)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $key }}: {{ $value }}
                        </span>
                    @endforeach
                @else
                    <span class="text-gray-500">Default variant</span>
                @endif
            </div>
        </div>

        <!-- Edit Form -->
        <form wire:submit="updateVariant" class="space-y-6">
            <!-- Variant Selection -->
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Variant Configuration</h2>
                
                @if($availableVariants)
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach($availableVariants as $variantName => $values)
                            <div>
                                <label for="variant_{{ $loop->index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $variantName }} *
                                </label>
                                <select wire:model="variant_values.{{ $variantName }}" id="variant_{{ $loop->index }}" 
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select {{ $variantName }}</option>
                                    @foreach($values as $valueId => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                    @error('variant_values') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @else
                    <p class="text-sm text-gray-500">No variant types available. Please create variant types first.</p>
                @endif
            </div>

            <!-- Pricing and Inventory -->
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Pricing & Inventory</h2>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₹) *</label>
                        <input type="number" wire:model="price" id="price" step="0.01" min="0"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                        <input type="number" wire:model="stock" id="stock" min="0"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                        @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                        <input type="text" wire:model="sku" id="sku"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                        @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Variant Images -->
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Variant Images</h2>
                    <span class="text-sm text-gray-500">{{ $variant->images->count() }} images</span>
                </div>

                @if($variant->images->count() > 0)
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                        @foreach($variant->images as $image)
                            <div class="relative group">
                                <img src="{{ $image->image_path }}" 
                                     alt="Variant image" 
                                     class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                @if($image->is_primary)
                                    <span class="absolute top-1 left-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Primary
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No images uploaded for this variant</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <button type="button" 
                        wire:click="deleteVariant" 
                        wire:confirm="Are you sure you want to delete this variant? This action cannot be undone."
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Variant
                </button>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.variants', $product) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Variant
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>