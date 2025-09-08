<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Product Variants</h3>
            <p class="text-sm text-gray-500">Manage different variant combinations of this product</p>
        </div>
        @if (!$showAddForm)
            <button type="button" wire:click="showAddVariantForm"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Combination
            </button>
        @endif
    </div>

    <!-- Add New Combination Form -->
    @if ($showAddForm)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-md font-medium text-gray-900">Add New Variant Combination</h4>
                <button type="button" wire:click="hideAddVariantForm" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Variant Types -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant Types *</label>
                    <div class="space-y-2">
                        @foreach ($availableVariants as $variant)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model.live="selectedVariantTypes"
                                    value="{{ $variant->id }}" id="variant-type-{{ $variant->id }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="variant-type-{{ $variant->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $variant->variant_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedVariantTypes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Variant Values -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant Values *</label>
                    <div class="space-y-2">
                        @foreach ($selectedVariantTypes as $variantId)
                            @php $variant = $availableVariants->find($variantId); @endphp
                            @if ($variant && isset($availableVariantValues[$variant->variant_name]))
                                <div class="mb-3">
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ $variant->variant_name }}</label>
                                    <select wire:model.live="selectedVariantValues.{{ $variantId }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select {{ $variant->variant_name }}</option>
                                        @foreach ($availableVariantValues[$variant->variant_name] as $valueId => $value)
                                            <option value="{{ $valueId }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @error('selectedVariantValues')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" step="0.01" wire:model="new_combination.price" placeholder="0.00"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('new_combination.price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                    <input type="number" wire:model="new_combination.stock" placeholder="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('new_combination.stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <div class="flex">
                        <input type="text" wire:model="new_combination.sku"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="button" wire:click="generateCombinationSKU"
                            class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-200 text-sm">
                            Generate
                        </button>
                    </div>
                    @error('new_combination.sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Combination Image -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Combination Image</label>

                @if ($new_variant_image_preview)
                    <div class="mb-3">
                        <div class="relative inline-block">
                            <img src="{{ $new_variant_image_preview }}"
                                class="w-20 h-20 object-cover rounded-lg border border-gray-300">
                            <button type="button" wire:click="removeNewVariantImage"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                ×
                            </button>
                        </div>
                    </div>
                @endif

                <input type="file" wire:model="new_combination.variant_image" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('new_combination.variant_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-4 pt-4 border-t border-blue-200">
                <button type="button" wire:click="hideAddVariantForm"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" wire:click="addCombination"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Combination
                </button>
            </div>
        </div>
    @endif

    <!-- Existing Combinations -->
    @if (count($variantCombinations) > 0)
        <div class="space-y-4">
            @foreach ($variantCombinations as $index => $combination)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    @if ($editingCombination === $index)
                        <!-- Edit Form -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 -m-4">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-medium text-gray-900">Edit Variant Combination</h5>
                                <button type="button" wire:click="cancelEdit"
                                    class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- Variant Values Display (Read-only) -->
                                <div class="col-span-full">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant
                                        Combination</label>
                                    <div class="flex flex-wrap gap-2">
                                        @if (isset($combination['variant_values_data']))
                                            @foreach ($combination['variant_values_data'] as $variantName => $value)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $variantName }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        @elseif(isset($combination['variant_values']))
                                            @foreach (json_decode($combination['variant_values'], true) as $variantName => $value)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $variantName }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <!-- Edit Price -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                    <input type="number" step="0.01" wire:model="editing_combination.price"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                    @error('editing_combination.price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Edit Stock -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity
                                        *</label>
                                    <input type="number" wire:model="editing_combination.stock"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                    @error('editing_combination.stock')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Edit SKU -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                    <input type="text" wire:model="editing_combination.sku"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                    @error('editing_combination.sku')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Edit Combination Image -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Combination Image</label>

                                @if ($editing_combination_image_preview)
                                    <div class="mb-3">
                                        <div class="relative inline-block">
                                            <img src="{{ $editing_combination_image_preview }}"
                                                class="w-20 h-20 object-cover rounded-lg border border-gray-300">
                                            <button type="button" wire:click="removeEditingCombinationImage"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                                ×
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <input type="file" wire:model="editing_combination.variant_image" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                @error('editing_combination.variant_image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Edit Action Buttons -->
                            <div class="flex justify-end space-x-3 mt-4 pt-4 border-t border-yellow-200">
                                <button type="button" wire:click="cancelEdit"
                                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" wire:click="updateCombination"
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                    Update Combination
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Display Mode -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                @if (isset($combination['image']) && $combination['image'])
                                    <img src="{{ $combination['image'] }}"
                                        class="w-16 h-16 object-cover rounded-lg border border-gray-300">
                                @else
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <div>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @if (isset($combination['variant_values_data']))
                                            @foreach ($combination['variant_values_data'] as $variantName => $value)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $variantName }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        @elseif(isset($combination['variant_values']))
                                            @foreach (json_decode($combination['variant_values'], true) as $variantName => $value)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $variantName }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500 space-x-4">
                                        @if (isset($combination['price']) && $combination['price'])
                                            <span>₹{{ number_format($combination['price'], 2) }}</span>
                                        @endif
                                        <span>Stock: {{ $combination['stock'] }}</span>
                                        @if (isset($combination['sku']) && $combination['sku'])
                                            <span>SKU: {{ $combination['sku'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <button type="button" wire:click="editCombination({{ $index }})"
                                    class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="deleteCombination({{ $index }})"
                                    wire:confirm="Are you sure you want to delete this variant combination?"
                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        @if (!$showAddForm)
            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No variant combinations added yet</h3>
                <p class="mt-2 text-sm text-gray-500">Add combinations like Color + Size to give customers more
                    options.</p>
                <button type="button" wire:click="showAddVariantForm"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Add First Combination
                </button>
            </div>
        @endif
    @endif
</div>