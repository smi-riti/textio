<div class="min-h-screen bg-gray-50 p-4 lg:p-8">
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="mb-8 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Products</h1>
                <p class="mt-2 text-gray-600">Manage your custom printing products</p>
            </div>
            <div class="flex flex-col space-y-2 sm:flex-row sm:space-x-3 sm:space-y-0">
                <a href="{{ route('products.create') }}" 
                   class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Product
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 rounded-lg bg-white p-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search products, SKU..."
                           class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select wire:model.live="categoryFilter" class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                    <select wire:model.live="brandFilter" class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="statusFilter" class="w-full p-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="overflow-hidden rounded-lg bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                <button wire:click="sortBy('name')" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Product</span>
                                    @if($sortField === 'name')
                                        @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Brand</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                <button wire:click="sortBy('price')" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Price</span>
                                    @if($sortField === 'price')
                                        @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-16 w-16 flex-shrink-0">
                                            @if($product->images->where('is_primary', true)->first())
                                                <img class="h-16 w-16 rounded-lg object-cover" 
                                                     src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}" 
                                                     alt="{{ $product->name }}">
                                            @else
                                                <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-200">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                            @if($product->featured)
                                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                    Featured
                                                </span>
                                            @endif
                                            @if($product->is_customizable)
                                                <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                                                    Customizable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $product->category?->title ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $product->brand?->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="text-green-600 font-medium">{{ $product->formatted_discount_price }}</div>
                                    @if($product->price && $product->discount_price && $product->discount_price < $product->price)
                                        <div class="text-sm text-gray-500 line-through">{{ $product->formatted_price }}</div>
                                        <div class="text-xs text-red-500">{{ $product->saving_percentage }}% OFF</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        {{ $product->quantity > 10 ? 'bg-green-100 text-green-800' : 
                                           ($product->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $product->quantity }} units
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <button wire:click="toggleStatus({{ $product->id }})" 
                                                class="group relative inline-flex w-9 h-5 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $product->status ? 'bg-green-500 focus:ring-green-500' : 'bg-gray-300 hover:bg-gray-400 focus:ring-gray-400' }}"
                                                role="switch" 
                                                aria-checked="{{ $product->status ? 'true' : 'false' }}">
                                            <span class="sr-only">Toggle product status</span>
                                            <span aria-hidden="true" 
                                                  class="w-4 h-4 pointer-events-none inline-block rounded-full bg-white shadow-lg transform ring-0 transition-all duration-200 ease-in-out group-hover:shadow-xl {{ $product->status ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                        <span class="ml-3 text-sm font-medium {{ $product->status ? 'text-green-700' : 'text-gray-500' }}">
                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="toggleFeatured({{ $product->id }})"
                                                class="group relative inline-flex w-9 h-5 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $product->featured ? 'bg-yellow-400 focus:ring-yellow-500' : 'bg-gray-300 hover:bg-gray-400 focus:ring-gray-400' }}"
                                                role="switch" 
                                                aria-checked="{{ $product->featured ? 'true' : 'false' }}"
                                                title="{{ $product->featured ? 'Remove from featured' : 'Add to featured' }}">
                                            <span class="sr-only">Toggle featured status</span>
                                            <span aria-hidden="true" 
                                                  class="w-4 h-4 pointer-events-none inline-block rounded-full bg-white shadow-lg transform ring-0 transition-all duration-200 ease-in-out group-hover:shadow-xl {{ $product->featured ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                        </button>
                                        <a href="{{ route('products.edit', $product->slug) }}" 
                                           class="rounded bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-200">
                                            Edit
                                        </a>
                                        
                                        <button wire:click="confirmDelete({{ $product->id }})" 
                                                class="rounded bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-6v2a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first product.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('products.create') }}" 
                                           class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <!-- Results Info -->
        <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
            <div>
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm">Show:</label>
                <select wire:model.live="perPage" id="perPage" class="rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <livewire:components.confirmation-modal />
</div>
