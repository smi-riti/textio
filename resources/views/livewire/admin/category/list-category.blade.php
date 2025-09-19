<div class="max-w-7xl mx-auto  sm:px-6 lg:px-8">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('message') }}</p>
                </div>
                <button type="button" class="text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-medium text-[#171717]">Categories Management</h2>
            <div class="mt-3 sm:mt-0 flex space-x-4">
                <button wire:click="setTab('active')" 
                        class="px-3 py-2 text-sm font-medium {{ $tab === 'active' ? 'text-[#8f4da7] border-b-2 border-[#8f4da7]' : 'text-gray-500 hover:text-gray-700' }} transition-colors">
                    Active Categories
                </button>
                <button wire:click="setTab('trash')" 
                        class="px-3 py-2 text-sm font-medium {{ $tab === 'trash' ? 'text-[#8f4da7] border-b-2 border-[#8f4da7]' : 'text-gray-500 hover:text-gray-700' }} transition-colors">
                    Trash
                </button>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="max-w-xs">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="search" id="search" 
                           class="block w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] sm:text-sm transition-colors"
                           placeholder="Search categories...">
                </div>
            </div>
            @if($tab !== 'trash')
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#8f4da7] hover:bg-[#7a3d92] focus:ring-2 focus:ring-offset-2 focus:ring-[#8f4da7] transition-colors shadow-sm">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create New Category
            </a>
            @endif
        </div>
    </div>
    
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="mt-4 flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Details</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-16 w-16 flex-shrink-0 relative group">
                                            @if($category->image)
                                                <img class="h-16 w-16 rounded-lg object-cover border border-gray-200 hover:shadow-md transition-shadow" 
                                                     src="{{ $category->image }}" 
                                                     alt="{{ $category->title }}" 
                                                     loading="lazy">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="h-16 w-16 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-[#171717]">{{ $category->title }}</div>
                                            <div class="text-sm text-gray-500 font-mono">{{ $category->slug }}</div>
                                            @if($category->children_count > 0)
                                                <div class="text-xs text-[#8f4da7] mt-1 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                    </svg>
                                                    {{ $category->children_count }} sub-categories
                                                </div>
                                            @endif
                                            @if($category->products_count > 0)
                                                <div class="text-xs text-green-600 mt-1 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                    {{ $category->products_count }} products
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($category->parent)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $category->parent->title }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">Root Category</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" 
                                                       wire:model.live="category.is_active" 
                                                       wire:click="toggleStatus('{{ $category->id }}')"
                                                       class="sr-only peer" 
                                                       {{ $category->is_active ? 'checked' : '' }}
                                                       wire:loading.attr="disabled">
                                                <div class="w-11 h-6 bg-gray-200 rounded-full peer 
                                                            peer-focus:outline-none peer-focus:ring-4 
                                                            peer-focus:ring-purple-300 
                                                            peer-checked:after:translate-x-full 
                                                            rtl:peer-checked:after:-translate-x-full 
                                                            peer-checked:after:border-white 
                                                            after:content-[''] 
                                                            after:absolute 
                                                            after:top-[2px] 
                                                            after:start-[2px] 
                                                            after:bg-white 
                                                            after:border-gray-300 
                                                            after:border 
                                                            after:rounded-full 
                                                            after:h-5 
                                                            after:w-5 
                                                            after:transition-all 
                                                            peer-checked:bg-[#8f4da7] transition-colors">
                                                </div>
                                            </label>
                                            <span class="ml-3 text-sm font-medium {{ $category->is_active ? 'text-green-700' : 'text-gray-500' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('admin.categories.view', $category->slug) }}" 
                                               class="text-gray-600 hover:text-gray-900 transition-colors"
                                               title="View category">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            @if(!$category->trashed())
                                                <a href="{{ route('admin.categories.edit', $category->slug) }}" 
                                                   class="text-[#8f4da7] hover:text-[#7a3d92] transition-colors"
                                                   title="Edit category">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <button wire:click="confirmDelete('{{ $category->id }}')" 
                                                        class="text-red-600 hover:text-red-900 transition-colors"
                                                        title="Delete category"
                                                        wire:loading.attr="disabled">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <button wire:click="confirmRestore('{{ $category->id }}')" 
                                                        class="text-green-600 hover:text-green-900 transition-colors"
                                                        title="Restore category"
                                                        wire:loading.attr="disabled">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <h3 class="mt-2 text-lg font-medium text-[#171717]">No categories found</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if($tab === 'trash')
                                                No deleted categories to restore.
                                            @else
                                                Get started by creating your first category.
                                            @endif
                                        </p>
                                        @if($tab !== 'trash')
                                            <div class="mt-6">
                                                <a href="{{ route('admin.categories.create') }}" 
                                                   class="inline-flex items-center rounded-lg bg-[#8f4da7] px-4 py-2 text-sm font-medium text-white hover:bg-[#7a3d92] focus:ring-2 focus:ring-[#8f4da7] focus:ring-offset-2 transition-colors shadow-sm">
                                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Create Category
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination (if applicable) -->
    @if(method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @endif

    @livewire('components.confirmation-modal')
</div>