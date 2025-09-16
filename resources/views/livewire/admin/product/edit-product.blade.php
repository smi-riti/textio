<div class="p-6 bg-white rounded-lg shadow-lg">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Product</h1>
            <p class="text-gray-600 mt-1">Update product information by clicking the edit button for each field</p>
        </div>
        <a href="{{ route('admin.products.list') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Products
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Product Details Card -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Product Information</h2>
        
        <!-- Name Field -->
        <div class="mb-6 border-b pb-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    @if($editingField === 'name')
                        <div class="flex items-center space-x-3">
                            <input type="text" wire:model="tempValue" 
                                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter product name">
                            <button wire:click="saveField('name')" 
                                    class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center"
                                    wire:loading.attr="disabled"
                                    wire:target="saveField('name')">
                                <span wire:loading.remove wire:target="saveField('name')">Save</span>
                                <span wire:loading wire:target="saveField('name')" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Saving...
                                </span>
                            </button>
                            <button wire:click="cancelEdit" 
                                    class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Cancel
                            </button>
                        </div>
                        @error('tempValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @else
                        <div class="flex items-center justify-between">
                            <p class="text-gray-900 text-lg">{{ $product->name ?? 'Not set' }}</p>
                            <button wire:click="startEdit('name')" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Edit
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Description Field -->
        <div class="mb-6 border-b pb-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    @if($editingField === 'description')
                        <div class="space-y-3">
                            <textarea wire:model="tempValue" rows="4"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Enter product description"></textarea>
                            <div class="flex space-x-3">
                                <button wire:click="saveField('description')" 
                                        class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    Save
                                </button>
                                <button wire:click="cancelEdit" 
                                        class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                    Cancel
                                </button>
                            </div>
                        </div>
                        @error('tempValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @else
                        <div class="flex items-start justify-between">
                            <p class="text-gray-900 flex-1">{{ $product->description ?? 'No description set' }}</p>
                            <button wire:click="startEdit('description')" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm ml-4">
                                Edit
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Price Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border-b pb-4">
            <!-- Regular Price -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price</label>
                @if($editingField === 'price')
                    <div class="flex items-center space-x-3">
                        <input type="number" step="0.01" wire:model="tempValue" 
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                        <button wire:click="saveField('price')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                    @error('tempValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @else
                    <div class="flex items-center justify-between">
                        <p class="text-gray-900 text-lg">{{ $product->formatted_price ?? 'Not set' }}</p>
                        <button wire:click="startEdit('price')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Edit
                        </button>
                    </div>
                @endif
            </div>

            <!-- Discount Price -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Discount Price</label>
                @if($editingField === 'discount_price')
                    <div class="flex items-center space-x-3">
                        <input type="number" step="0.01" wire:model="tempValue" 
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                        <button wire:click="saveField('discount_price')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                    @error('tempValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @else
                    <div class="flex items-center justify-between">
                        <p class="text-gray-900 text-lg">{{ $product->formatted_discount_price ?? 'Not set' }}</p>
                        <button wire:click="startEdit('discount_price')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Edit
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Category and Brand -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border-b pb-4">
            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                @if($editingField === 'category_id')
                    <div class="flex items-center space-x-3">
                        <select wire:model="tempValue" 
                                class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                        <button wire:click="saveField('category_id')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                    @error('tempValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @else
                    <div class="flex items-center justify-between">
                        <p class="text-gray-900">{{ $product->category->title ?? 'No category selected' }}</p>
                        <button wire:click="startEdit('category_id')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Edit
                        </button>
                    </div>
                @endif
            </div>

            <!-- Brand -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                @if($editingField === 'brand_id')
                    <div class="flex items-center space-x-3">
                        <select wire:model="tempValue" 
                                class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        <button wire:click="saveField('brand_id')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                    @error('tempValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @else
                    <div class="flex items-center justify-between">
                        <p class="text-gray-900">{{ $product->brand->name ?? 'No brand selected' }}</p>
                        <button wire:click="startEdit('brand_id')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Edit
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Toggles -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 border-b pb-4">
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                @if($editingField === 'status')
                    <div class="flex items-center space-x-3">
                        <select wire:model="tempValue" 
                                class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <button wire:click="saveField('status')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->status ? 'Active' : 'Inactive' }}
                        </span>
                        <button wire:click="startEdit('status')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Edit
                        </button>
                    </div>
                @endif
            </div>

            <!-- Is Customizable -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customizable</label>
                @if($editingField === 'is_customizable')
                    <div class="flex items-center space-x-3">
                        <select wire:model="tempValue" 
                                class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <button wire:click="saveField('is_customizable')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_customizable ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_customizable ? 'Yes' : 'No' }}
                        </span>
                        <button wire:click="startEdit('is_customizable')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Edit
                        </button>
                    </div>
                @endif
            </div>

            <!-- Featured -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                @if($editingField === 'featured')
                    <div class="flex items-center space-x-3">
                        <select wire:model="tempValue" 
                                class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <button wire:click="saveField('featured')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->featured ? 'Yes' : 'No' }}
                        </span>
                        <button wire:click="startEdit('featured')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Edit
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Print Area -->
        <div class="mb-6 border-b pb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Print Areas</label>
            @if($editingField === 'print_area')
                <div class="space-y-3">
                    @foreach($tempArray as $index => $area)
                        <div class="flex items-center space-x-3">
                            <input type="text" wire:model="tempArray.{{ $index }}" 
                                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Print area description">
                            <button wire:click="removePrintArea({{ $index }})" 
                                    class="bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded">
                                Remove
                            </button>
                        </div>
                    @endforeach
                    <div class="flex space-x-3">
                        <button wire:click="addPrintArea" 
                                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Add Area
                        </button>
                        <button wire:click="saveField('print_area')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
                @error('tempArray') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @else
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        @if($product->print_area && count($product->print_area) > 0)
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($product->print_area as $area)
                                    <li class="text-gray-900">{{ $area }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-900">No print areas defined</p>
                        @endif
                    </div>
                    <button wire:click="startEdit('print_area')" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm ml-4">
                        Edit
                    </button>
                </div>
            @endif
        </div>

        <!-- SEO Fields -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Meta Title -->
            <div class="border-b pb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                @if($editingField === 'meta_title')
                    <div class="flex items-center space-x-3">
                        <input type="text" wire:model="tempValue" 
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Enter meta title">
                        <button wire:click="saveField('meta_title')" 
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="cancelEdit" 
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                    @error('tempValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @else
                    <div class="flex items-center justify-between">
                        <p class="text-gray-900 flex-1">{{ $product->meta_title ?? 'Not set' }}</p>
                        <button wire:click="startEdit('meta_title')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm ml-4">
                            Edit
                        </button>
                    </div>
                @endif
            </div>

            <!-- Meta Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                @if($editingField === 'meta_description')
                    <div class="space-y-3">
                        <textarea wire:model="tempValue" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Enter meta description"></textarea>
                        <div class="flex space-x-3">
                            <button wire:click="saveField('meta_description')" 
                                    class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                Save
                            </button>
                            <button wire:click="cancelEdit" 
                                    class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Cancel
                            </button>
                        </div>
                    </div>
                    @error('tempValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @else
                    <div class="flex items-start justify-between">
                        <p class="text-gray-900 flex-1">{{ $product->meta_description ?? 'Not set' }}</p>
                        <button wire:click="startEdit('meta_description')" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm ml-4">
                            Edit
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h3 class="text-md font-semibold text-gray-700 mb-2">Additional Information</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-600">Product ID:</span>
                <span class="text-gray-900">{{ $product->id }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Slug:</span>
                <span class="text-gray-900">{{ $product->slug }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Created:</span>
                <span class="text-gray-900">{{ $product->created_at->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Updated:</span>
                <span class="text-gray-900">{{ $product->updated_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>
</div>