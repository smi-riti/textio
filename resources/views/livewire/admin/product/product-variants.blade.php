<div class="space-y-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Progress -->
    @if ($isUploading)
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center text-blue-700 text-sm">
                <svg class="animate-spin h-4 w-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Uploading images...
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Product Variants</h2>
        @if ($product && !$showForm)
            <button 
                wire:click="showAddForm" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Add Variant</span>
                <span wire:loading>Loading...</span>
            </button>
        @endif
    </div>

    <!-- Add/Edit Form -->
    @if ($showForm)
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            <h3 class="text-lg font-semibold mb-4">
                {{ $editingCombination ? 'Edit Variant' : 'Add New Variant' }}
            </h3>

            <!-- Variant Types Selection -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Variant Types</label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($availableVariants as $variant)
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                value="{{ $variant['id'] }}"
                                wire:model.live="selectedVariantTypes"
                                class="form-checkbox"
                            >
                            <span class="ml-2">{{ $variant['variant_name'] }}</span>
                        </label>
                    @endforeach
                </div>
                @error('selectedVariantTypes') 
                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Variant Values Selection -->
            @if (!empty($availableVariantValues))
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Variant Values</label>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($availableVariantValues as $variantName => $values)
                            @php
                                $variantId = collect($availableVariants)->firstWhere('variant_name', $variantName)['id'] ?? null;
                            @endphp
                            @if ($variantId)
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">{{ $variantName }}</label>
                                    <select 
                                        wire:model="selectedVariantValues.{{ $variantId }}"
                                        class="form-select w-full"
                                    >
                                        <option value="">Select {{ $variantName }}</option>
                                        @foreach ($values as $id => $value)
                                            <option value="{{ $id }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedVariantValues.' . $variantId) 
                                        <span class="text-red-500 text-xs">{{ $message }}</span> 
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Combination Details -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                    <input 
                        type="number" 
                        step="0.01"
                        wire:model="combination.price"
                        class="form-input w-full"
                        placeholder="Optional"
                    >
                    @error('combination.price') 
                        <span class="text-red-500 text-xs">{{ $message }}</span> 
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Stock *</label>
                    <input 
                        type="number" 
                        wire:model="combination.stock"
                        class="form-input w-full"
                        placeholder="Required"
                    >
                    @error('combination.stock') 
                        <span class="text-red-500 text-xs">{{ $message }}</span> 
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">SKU</label>
                    <div class="flex">
                        <input 
                            type="text" 
                            wire:model="combination.sku"
                            class="form-input w-full"
                            placeholder="Auto-generated"
                        >
                        <button 
                            type="button"
                            wire:click="generateSKU"
                            class="ml-2 bg-gray-500 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm"
                        >
                            Generate
                        </button>
                    </div>
                    @error('combination.sku') 
                        <span class="text-red-500 text-xs">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <!-- Existing Images (Edit Mode) -->
            @if ($editingCombination && !empty($existingImages))
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Current Images</label>
                    <div class="grid grid-cols-4 gap-4">
                        @foreach ($existingImages as $image)
                            <div class="relative">
                                <img src="{{ $image['image_path'] }}" alt="Current Image" class="w-full h-20 object-cover rounded">
                                @if ($image['is_primary'])
                                    <span class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">Featured</span>
                                @endif
                                <button 
                                    type="button"
                                    wire:click="removeExistingImage({{ $image['id'] }})"
                                    class="absolute top-1 right-1 bg-red-500 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Featured Image Upload -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    {{ $editingCombination ? 'Replace Featured Image' : 'Featured Image *' }}
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                    @if ($featuredImagePreview)
                        <div class="flex items-center space-x-4">
                            <img src="{{ $featuredImagePreview }}" alt="Preview" class="w-20 h-20 object-cover rounded">
                            <button 
                                type="button"
                                wire:click="removeFeaturedImage"
                                class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                            >
                                Remove
                            </button>
                        </div>
                    @else
                        <input 
                            type="file" 
                            wire:model.live="featuredImage"
                            accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        >
                    @endif
                </div>
                @error('featuredImage') 
                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Gallery Images Upload -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    {{ $editingCombination ? 'Replace Gallery Images' : 'Gallery Images' }}
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                    @if (!empty($galleryImagePreviews))
                        <div class="grid grid-cols-4 gap-4 mb-4">
                            @foreach ($galleryImagePreviews as $index => $preview)
                                <div class="relative">
                                    <img src="{{ $preview }}" alt="Gallery Preview" class="w-full h-20 object-cover rounded">
                                    <button 
                                        type="button"
                                        wire:click="removeGalleryImage({{ $index }})"
                                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                                    >
                                        ×
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input 
                        type="file" 
                        wire:model.live="galleryImages"
                        accept="image/*"
                        multiple
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    >
                </div>
                @error('galleryImages.*') 
                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <button 
                    wire:click="hideForm"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                    wire:loading.attr="disabled"
                >
                    Cancel
                </button>
                <button 
                    wire:click="saveCombination"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                >
                    <span wire:loading.remove wire:target="saveCombination">{{ $editingCombination ? 'Update' : 'Add' }} Variant</span>
                    <span wire:loading wire:target="saveCombination">Processing...</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Variants List -->
    @if (!empty($variantCombinations))
        <div class="space-y-4">
            @foreach ($variantCombinations as $combination)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <!-- Combination Info -->
                        <div class="flex items-center space-x-4">
                            <!-- Featured Image -->
                            @if (isset($combination['images']) && ($featured = collect($combination['images'])->firstWhere('is_primary', true)))
                                <img src="{{ $featured['image_path'] }}" class="w-16 h-16 object-cover rounded-lg border border-gray-300">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg border flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Variant Details -->
                            <div>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @php
                                        $variantValues = $combination['variant_values'] ?? [];
                                        if (is_string($variantValues)) {
                                            $variantValues = json_decode($variantValues, true) ?? [];
                                        }
                                    @endphp
                                    @foreach ($variantValues as $name => $value)
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $name }}: {{ $value }}</span>
                                    @endforeach
                                </div>
                                <div class="text-sm text-gray-500 space-x-4">
                                    @if ($combination['price']) 
                                        <span>₹{{ number_format($combination['price'], 2) }}</span> 
                                    @endif
                                    <span>Stock: {{ $combination['stock'] }}</span>
                                    @if ($combination['sku']) 
                                        <span>SKU: {{ $combination['sku'] }}</span> 
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Gallery Preview -->
                            @if (isset($combination['images']) && ($gallery = collect($combination['images'])->where('is_primary', false)->take(2)))
                                <div class="flex space-x-1">
                                    @foreach ($gallery as $img)
                                        <img src="{{ $img['image_path'] }}" class="w-8 h-8 object-cover rounded">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <button 
                                wire:click="editCombination({{ $combination['id'] }})" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button 
                                wire:click="deleteCombination({{ $combination['id'] }})" 
                                wire:confirm="Are you sure you want to delete this variant?" 
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        @if (!$showForm)
            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No variants found</h3>
                <p class="mt-2 text-sm text-gray-500">Add your first variant combination to get started.</p>
                @if ($product)
                    <button 
                        wire:click="showAddForm" 
                        class="mt-4 inline-flex px-4 py-2 bg-blue-600 text-white rounded-lg"
                    >
                        Add First Variant
                    </button>
                @endif
            </div>
        @endif
    @endif
</div>