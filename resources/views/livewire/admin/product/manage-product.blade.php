<div class="container mx-auto p-4">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Product Form -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">{{ $editingProductId ? 'Edit Product' : 'Add New Product' }}</h2>
        <form wire:submit.prevent="saveProduct">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" id="name" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category_id" wire:model="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Brand -->
                <div>
                    <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand (Optional)</label>
                    <select id="brand_id" wire:model="brand_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Unit Price -->
                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
                    <input type="number" step="0.01" id="unit_price" wire:model="unit_price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('unit_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Current Stock -->
                <div>
                    <label for="current_stock" class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" id="current_stock" wire:model="current_stock" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('current_stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Minimum Quantity -->
                <div>
                    <label for="min_qty" class="block text-sm font-medium text-gray-700">Minimum Quantity</label>
                    <input type="number" id="min_qty" wire:model="min_qty" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('min_qty') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Discount -->
                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
                    <input type="number" step="0.01" id="discount" wire:model="discount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('discount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Discount Type -->
                <div>
                    <label for="discount_type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                    <select id="discount_type" wire:model="discount_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="flat">Flat</option>
                        <option value="percent">Percent</option>
                    </select>
                    @error('discount_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Shipping Cost -->
                <div>
                    <label for="shipping_cost" class="block text-sm font-medium text-gray-700">Shipping Cost</label>
                    <input type="number" step="0.01" id="shipping_cost" wire:model="shipping_cost" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('shipping_cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                    <input type="text" id="tags" wire:model="tags" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="comma,separated,tags">
                    @error('tags') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Published -->
                <div class="flex items-center">
                    <input type="checkbox" id="published" wire:model="published" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="published" class="ml-2 block text-sm text-gray-900">Published</label>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" wire:model="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Meta Title -->
            <div class="mt-4">
                <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                <input type="text" id="meta_title" wire:model="meta_title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('meta_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Meta Description -->
            <div class="mt-4">
                <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                <textarea id="meta_description" wire:model="meta_description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                @error('meta_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Thumbnail Image -->
            <div class="mt-4">
                <label for="thumbnail_img" class="block text-sm font-medium text-gray-700">Thumbnail Image</label>
                <input type="file" id="thumbnail_img" wire:model="thumbnail_img" class="mt-1 block w-full">
                @if ($thumbnailPreview)
                    <img src="{{ $thumbnailPreview }}" alt="Preview" class="mt-2 h-20 w-20 object-cover rounded">
                @elseif ($thumbnail_img)
                    <div wire:loading wire:target="thumbnail_img">Uploading...</div>
                @endif
                @error('thumbnail_img') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex space-x-4">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    {{ $editingProductId ? 'Update Product' : 'Add Product' }}
                </button>
                @if ($editingProductId)
                    <button type="button" wire:click="resetForm" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Product List -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Product List</h2>
            <label class="flex items-center">
                <input type="checkbox" wire:model="showDeleted" class="mr-2">
                Show Deleted
            </label>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr class="{{ $product->trashed() ? 'bg-gray-100' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->thumbnail_img)
                                    <img src="{{ $product->thumbnail_img }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->brand->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($product->unit_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->current_stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="{{ $product->trashed() ? 'text-red-600' : ($product->published ? 'text-green-600' : 'text-red-600') }}">
                                    {{ $product->trashed() ? 'Deleted' : ($product->published ? 'Published' : 'Unpublished') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                @if ($product->trashed())
                                    <button wire:click="restoreProduct({{ $product->id }})" class="text-green-600 hover:text-green-900">Restore</button>
                                @else
                                    <button wire:click="editProduct({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <button wire:click="deleteProduct({{ $product->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>