<div class="w-full max-w-6xl mx-auto md:p-6">
    <!-- Flash Messages -->
    <div class="space-y-3 mb-6">
        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-medium text-[#171717]">Product Variants</h2>
        @if (!$showAddForm)
            <button 
                wire:click="showAddVariantForm" 
                class="px-5 py-2.5 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3d92] transition-colors font-medium"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Add Variant</span>
                <span wire:loading>Loading...</span>
            </button>
        @endif
    </div>

    <!-- Add/Edit Form -->
    @if ($showAddForm)
        <div class="bg-white rounded-xl border-gray-100 p-4 mb-6">
            <h3 class="text-lg font-medium text-[#171717] mb-4">
                {{ $editingCombination !== null ? 'Edit Variant' : 'Add New Variant' }}
            </h3>

            <!-- Debug Output -->
            @if ($editingCombination !== null)
                <div class="debug hidden">{{ json_encode($selectedVariantValues) }}</div>
            @endif

            <!-- Upload Progress -->
            @if ($isUploading)
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-6">
                    <div class="flex items-center text-blue-700 text-sm">
                        <svg class="animate-spin h-4 w-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading images...
                    </div>
                </div>
            @endif

            <!-- Variant Types Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Variant Types</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($availableVariants as $variant)
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <input 
                                type="checkbox" 
                                value="{{ $variant->id }}"
                                wire:model.live="selectedVariantTypes"
                                class="h-4 w-4 text-[#8f4da7] border-gray-300 rounded focus:ring-[#8f4da7] transition-colors"
                            >
                            <span class="ml-2 text-gray-700">{{ $variant->variant_name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('selectedVariantTypes') 
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Variant Values Selection -->
            @if (!empty($availableVariantValues))
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Variant Values</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($availableVariantValues as $variantName => $values)
                            @php
                                $variantId = $availableVariants->firstWhere('variant_name', $variantName)->id;
                            @endphp
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $variantName }}</label>
                                <select 
                                    wire:model.live="selectedVariantValues.{{ $variantId }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"
                                >
                                    <option value="">Select {{ $variantName }}</option>
                                    @foreach ($values as $id => $value)
                                        <option value="{{ $id }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('selectedVariantValues.' . $variantId) 
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Combination Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <input 
                        type="number" 
                        step="0.01"
                        wire:model="{{ $editingCombination !== null ? 'editing_combination.price' : 'new_combination.price' }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"
                        placeholder="Optional"
                    >
                    @error($editingCombination !== null ? 'editing_combination.price' : 'new_combination.price') 
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                    <input 
                        type="number" 
                        wire:model="{{ $editingCombination !== null ? 'editing_combination.stock' : 'new_combination.stock' }}"
                        class="w-full px-4 py-3 border border-gray-300 lady rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"
                        placeholder="Required"
                    >
                    @error($editingCombination !== null ? 'editing_combination.stock' : 'new_combination.stock') 
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <div class="flex">
                        <input 
                            type="text" 
                            wire:model="{{ $editingCombination !== null ? 'editing_combination.sku' : 'new_combination.sku' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"
                            placeholder="Auto-generated"
                        >
                        <button 
                            type="button"
                            wire:click="generateCombinationSKU"
                            class="px-4 bg-gray-100 text-gray-700 border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-200 transition-colors"
                        >
                            Generate
                        </button>
                    </div>
                    @error($editingCombination !== null ? 'editing_combination.sku' : 'new_combination.sku') 
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Existing Images (Edit Mode) -->
            @if ($editingCombination !== null && !empty($existingImages))
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Current Images</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($existingImages as $index => $image)
                            <div class="relative group">
                                <img src="{{ $image['image_path'] }}" alt="Current Image" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                @if ($image['is_primary'])
                                    <span class="absolute top-2 left-2 bg-[#8f4da7] text-white text-xs px-2 py-1 rounded">Featured</span>
                                @endif
                                <button 
                                    type="button"
                                    wire:click="removeExistingImage({{ $index }})"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Featured Image Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    {{ $editingCombination !== null ? 'Replace Featured Image' : 'Featured Image *' }}
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-[#8f4da7] transition-colors">
                    @if ($editingCombination !== null ? $editing_featured_image_preview : $new_featured_image_preview)
                        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <img src="{{ $editingCombination !== null ? $editing_featured_image_preview : $new_featured_image_preview }}" alt="Preview" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                            <button 
                                type="button"
                                wire:click="{{ $editingCombination !== null ? 'removeEditingFeaturedImage' : 'removeNewFeaturedImage' }}"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm"
                            >
                                Remove
                            </button>
                        </div>
                    @elseif ($editingCombination !== null && $existingImages)
                        @php
                            $featured = collect($existingImages)->firstWhere('is_primary', true);
                        @endphp
                        @if ($featured)
                            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <img src="{{ $featured['image_path'] }}" alt="Current Featured Image" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                                <button 
                                    type="button"
                                    wire:click="removeExistingImage({{ array_search($featured, $existingImages) }})"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm"
                                >
                                    Remove
                                </button>
                            </div>
                        @endif
                    @else
                        <input 
                            type="file" 
                            wire:model="{{ $editingCombination !== null ? 'editing_featured_image' : 'new_featured_image' }}"
                            accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-[#8f4da7] file:text-white hover:file:bg-[#7a3d92] transition-colors"
                        >
                    @endif
                </div>
                @error($editingCombination !== null ? 'editing_featured_image' : 'new_featured_image') 
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Gallery Images Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    {{ $editingCombination !== null ? 'Replace Gallery Images' : 'Gallery Images' }}
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-[#8f4da7] transition-colors">
                    @if (!empty($editingCombination !== null ? $editing_gallery_images_preview : $new_gallery_images_preview))
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-4">
                            @foreach (($editingCombination !== null ? $editing_gallery_images_preview : $new_gallery_images_preview) as $index => $preview)
                                <div class="relative group">
                                    <img src="{{ $preview }}" alt="Gallery Preview" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                    <button 
                                        type="button"
                                        wire:click="{{ $editingCombination !== null ? 'removeEditingGalleryImage' : 'removeNewGalleryImage' }}({{ $index }})"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                    >
                                        ×
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input 
                        type="file" 
                        wire:model="{{ $editingCombination !== null ? 'editing_gallery_images' : 'new_gallery_images' }}"
                        accept="image/*"
                        multiple
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-[#8f4da7] file:text-white hover:file:bg-[#7a3d92] transition-colors"
                    >
                </div>
                @error($editingCombination !== null ? 'editing_gallery_images.*' : 'new_gallery_images.*') 
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <button 
                    wire:click="hideAddVariantForm"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium"
                    wire:loading.attr="disabled"
                >
                    Cancel
                </button>
                <button 
                    wire:click="addCombination"
                    class="px-5 py-2.5 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3d92] transition-colors font-medium mb-3 sm:mb-0"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                >
                    <span wire:loading.remove wire:target="addCombination">{{ $editingCombination !== null ? 'Update' : 'Add' }} Variant</span>
                    <span wire:loading wire:target="addCombination">Processing...</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Variants List -->
    @if (count($variantCombinations) > 0)
        <div class="space-y-4">
            @foreach ($variantCombinations as $index => $combination)
                <div class="bg-white rounded-xl border border-gray-100 p-4 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <!-- Combination Info -->
                        <div class="flex items-start sm:items-center gap-4 flex-1 min-w-0">
                            <!-- Featured Image -->
                            @if (isset($combination['images']) && ($featured = collect($combination['images'])->firstWhere('is_primary', true)))
                                <img src="{{ $featured['image_path'] }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Variant Details -->
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @php
                                        $variantValues = $combination['variant_values_data'] ?? json_decode($combination['variant_values'] ?? '[]', true);
                                    @endphp
                                    @foreach ($variantValues as $name => $value)
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#f3e8f7] text-[#8f4da7]">{{ $name }}: {{ $value }}</span>
                                    @endforeach
                                </div>
                                <div class="text-sm text-gray-500 space-y-1 sm:space-y-0 sm:space-x-4">
                                    @if ($combination['price']) 
                                        <span class="inline-block">₹{{ number_format($combination['price'], 2) }}</span> 
                                    @endif
                                    <span class="inline-block">Stock: {{ $combination['stock'] }}</span>
                                    @if ($combination['sku']) 
                                        <span class="inline-block truncate">SKU: {{ $combination['sku'] }}</span> 
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2 self-end sm:self-auto">
                            <button 
                                wire:click="editCombination({{ $index }})" 
                                class="p-2 text-[#8f4da7] hover:bg-[#f3e8f7] rounded-lg transition-colors"
                                title="Edit variant"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button 
                                wire:click="deleteCombination({{ $index }})" 
                                wire:confirm="Are you sure you want to delete this variant?" 
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="Delete variant"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Gallery Preview -->
                    @if (isset($combination['images']) && ($gallery = collect($combination['images'])->where('is_primary', false)->take(3)))
                        <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                            @foreach ($gallery as $img)
                                <img src="{{ $img['image_path'] }}" class="w-10 h-10 object-cover rounded border border-gray-200">
                            @endforeach
                            @if (count($combination['images']) > 4)
                                <div class="w-10 h-10 bg-gray-100 rounded border border-gray-200 flex items-center justify-center text-xs text-gray-500">
                                    +{{ count($combination['images']) - 3 }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        @if (!$showAddForm)
            <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No variants found</h3>
                <p class="mt-2 text-sm text-gray-500">Add your first variant combination to get started.</p>
                <button 
                    wire:click="showAddVariantForm" 
                    class="mt-4 px-5 py-2.5 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3d92] transition-colors font-medium"
                >
                    Add First Variant
                </button>
            </div>
        @endif
    @endif
</div>