<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Product Variants</h3>
            <p class="text-sm text-gray-500">Manage variants and images</p>
        </div>
        @if(!$showAddForm)
            <button type="button" wire:click="showAddVariantForm" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Combination
            </button>
        @endif
    </div>

    @if($isUploading)
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

    @if($showAddForm)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-md font-medium text-gray-900">Add Variant Combination</h4>
                <button type="button" wire:click="hideAddVariantForm" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant Types</label>
                    <div class="space-y-2">
                        @foreach($availableVariants as $variant)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model.live="selectedVariantTypes" value="{{ $variant->id }}" id="type-{{ $variant->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="type-{{ $variant->id }}" class="ml-2 text-sm text-gray-700">{{ $variant->variant_name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedVariantTypes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant Values</label>
                    <div class="space-y-2">
                        @foreach($selectedVariantTypes as $variantId)
                            @php $variant = $availableVariants->find($variantId); @endphp
                            @if($variant && isset($availableVariantValues[$variant->variant_name]))
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $variant->variant_name }}</label>
                                    <select wire:model.live="selectedVariantValues.{{ $variantId }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select</option>
                                        @foreach($availableVariantValues[$variant->variant_name] as $valueId => $value)
                                            <option value="{{ $valueId }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @error('selectedVariantValues') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" step="0.01" wire:model="new_combination.price" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('new_combination.price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                    <input type="number" wire:model="new_combination.stock" placeholder="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('new_combination.stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <div class="flex">
                        <input type="text" wire:model="new_combination.sku" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500">
                        <button type="button" wire:click="generateCombinationSKU" class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-200">Generate</button>
                    </div>
                    @error('new_combination.sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="md:col-span-3 mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image *</label>
                <input type="file" wire:model="new_featured_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @if($new_featured_image_preview)
                    <div class="mt-2 relative">
                        <img src="{{ $new_featured_image_preview }}" class="w-20 h-20 object-cover rounded-lg border border-gray-300">
                        <button wire:click="removeNewFeaturedImage" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">×</button>
                    </div>
                @endif
                @error('new_featured_image') 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                @elseif(!$new_featured_image && $showAddForm)
                    <p class="mt-1 text-sm text-yellow-600">Featured image required for variants.</p>
                @enderror
            </div>

            <div class="md:col-span-3 mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                <input type="file" wire:model="new_gallery_images" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                @if(!empty($new_gallery_images_preview))
                    <div class="mt-4 grid grid-cols-2 gap-2 md:grid-cols-4">
                        @foreach($new_gallery_images_preview as $index => $preview)
                            <div class="relative">
                                <img src="{{ $preview }}" class="w-full h-20 object-cover rounded-lg border border-gray-300">
                                <button wire:click="removeNewGalleryImage({{ $index }})" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center">×</button>
                            </div>
                        @endforeach
                    </div>
                @endif
                @error('new_gallery_images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end space-x-3 mt-4 pt-4 border-t border-blue-200">
                <button type="button" wire:click="hideAddVariantForm" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="button" wire:click="addCombination" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add</button>
            </div>
        </div>
    @endif

    @if(count($variantCombinations) > 0)
        <div class="space-y-4">
            @foreach($variantCombinations as $index => $combination)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md">
                    @if($editingCombination === $index)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 -m-4">
                            <div class="col-span-full mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Variant Combination</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($combination['variant_values_data'] as $name => $value)
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $name }}: {{ $value }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                    <input type="number" step="0.01" wire:model="editing_combination.price" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                    <input type="number" wire:model="editing_combination.stock" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                    <input type="text" wire:model="editing_combination.sku" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                            <div class="md:col-span-3 mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                                <input type="file" wire:model="editing_featured_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @if($editing_featured_image_preview)
                                    <div class="mt-2 relative">
                                        <img src="{{ $editing_featured_image_preview }}" class="w-20 h-20 object-cover rounded-lg border">
                                        <button wire:click="removeEditingFeaturedImage" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">×</button>
                                    </div>
                                @endif
                                @error('editing_featured_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-3 mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                                <input type="file" wire:model="editing_gallery_images" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                @if(!empty($editing_gallery_images_preview))
                                    <div class="mt-4 grid grid-cols-2 gap-2">
                                        @foreach($editing_gallery_images_preview as $idx => $preview)
                                            <div class="relative">
                                                <img src="{{ $preview }}" class="w-full h-20 object-cover rounded-lg border">
                                                <button wire:click="removeEditingGalleryImage({{ $idx }})" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @error('editing_gallery_images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex justify-end space-x-3 mt-4 pt-4 border-t border-yellow-200">
                                <button type="button" wire:click="cancelEdit" class="px-4 py-2 text-gray-700 bg-white border rounded-lg hover:bg-gray-50">Cancel</button>
                                <button type="button" wire:click="updateCombination" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Update</button>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                @if(isset($combination['images']) && ($featured = collect($combination['images'])->firstWhere('is_primary', true)))
                                    <img src="{{ $featured['image_path'] }}" class="w-16 h-16 object-cover rounded-lg border border-gray-300">
                                @else
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg border flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @foreach($combination['variant_values_data'] ?? json_decode($combination['variant_values'] ?? '[]', true) as $name => $value)
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $name }}: {{ $value }}</span>
                                        @endforeach
                                    </div>
                                    <div class="text-sm text-gray-500 space-x-4">
                                        @if($combination['price']) <span>₹{{ number_format($combination['price'], 2) }}</span> @endif
                                        <span>Stock: {{ $combination['stock'] }}</span>
                                        @if($combination['sku']) <span>SKU: {{ $combination['sku'] }}</span> @endif
                                    </div>
                                </div>
                                @if(isset($combination['images']) && $gallery = collect($combination['images'])->where('is_primary', false)->take(2))
                                    <div class="flex space-x-1">
                                        @foreach($gallery as $img)
                                            <img src="{{ $img['image_path'] }}" class="w-8 h-8 object-cover rounded">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <button wire:click="editCombination({{ $index }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button wire:click="deleteCombination({{ $index }})" wire:confirm="Delete?" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        @if(!$showAddForm)
            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No variants</h3>
                <p class="mt-2 text-sm text-gray-500">Add combinations for options.</p>
                <button wire:click="showAddVariantForm" class="mt-4 inline-flex px-4 py-2 bg-blue-600 text-white rounded-lg">Add First</button>
            </div>
        @endif
    @endif
</div>