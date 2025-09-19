<div class="max-w-7xl mx-auto  sm:px-6 lg:px-8">
    <div class="border border-gray-200 rounded-xl bg-white shadow-sm">

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center px-6 py-5 border-b border-gray-200 bg-gray-50">
            <div>
                <h2 class="text-xl font-medium text-[#171717]">Brand Management</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your brands and their settings</p>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mt-4 lg:mt-0">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] text-sm w-full sm:w-64 transition-colors"
                           placeholder="Search brands...">
                </div>

                <!-- Tabs -->
                <div class="flex space-x-1 bg-white rounded-lg p-1 border border-gray-200 shadow-sm">
                    <button 
                        wire:click="showList" 
                        class="px-3 py-2 text-sm font-medium rounded-md transition-all duration-200
                               {{ !$showDeleted ? 'bg-[#8f4da7] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Active
                    </button>
                    <button 
                        wire:click="showTrash" 
                        class="px-3 py-2 text-sm font-medium rounded-md transition-all duration-200
                               {{ $showDeleted ? 'bg-[#8f4da7] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Trash
                    </button>
                </div>

                <!-- Create button -->
                @if(!$showDeleted)
                    <a href="{{ route('admin.brand-add') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-[#8f4da7] text-white hover:bg-[#7a3d92] focus:ring-2 focus:ring-[#8f4da7] focus:ring-offset-2 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Add Brand
                    </a>
                @endif
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('message'))
            <div class="mx-6 mt-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="pl-6 pr-3 py-4 text-left">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Logo</span>
                        </th>
                        <th class="px-3 py-4 text-left">
                            <button wire:click="sortBy('name')" class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wide hover:text-gray-700 transition-colors">
                                <span>Name</span>
                                @if($sortBy === 'name')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd"/>
                                        @else
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168 13.71 7.23a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-3 py-4 text-left">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</span>
                        </th>
                        <th class="px-3 py-4 text-left">
                            <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wide hover:text-gray-700 transition-colors">
                                <span>Created</span>
                                @if($sortBy === 'created_at')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd"/>
                                        @else
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168 13.71 7.23a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="pl-3 pr-6 py-4 text-right">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($brands as $brand)
                        <tr class="hover:bg-gray-50 transition-colors {{ $brand->trashed() ? 'bg-red-50' : '' }}">
                            <!-- Logo -->
                            <td class="pl-6 pr-3 py-4">
                                @if ($brand->logo)
                                    <img src="{{ $brand->logo }}" 
                                         alt="{{ $brand->name }}" 
                                         class="w-12 h-12 rounded-lg object-contain bg-white p-1 border border-gray-200 shadow-sm">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Name -->
                            <td class="px-3 py-4">
                                <div class="font-medium text-[#171717]">{{ $brand->name }}</div>
                                @if($brand->description)
                                    <div class="text-gray-500 text-xs mt-1">{{ Str::limit($brand->description, 50) }}</div>
                                @endif
                            </td>
                            
                            <!-- Status -->
                            <td class="px-3 py-4">
                                @if($brand->trashed())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Deleted
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        @if($brand->is_active)
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Created Date -->
                            <td class="px-3 py-4 text-gray-500">
                                <div class="text-sm">{{ $brand->created_at->format('M d, Y') }}</div>
                                <div class="text-xs">{{ $brand->created_at->format('H:i') }}</div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="pl-3 pr-6 py-4">
                                <div class="flex justify-end items-center space-x-2">
                                    @if ($brand->trashed())
                                        <button wire:click="restoreBrand({{ $brand->id }})" 
                                                class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-800 rounded-lg hover:bg-green-100 transition-colors"
                                                title="Restore">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <button wire:click="permanentDelete({{ $brand->id }})" 
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 rounded-lg hover:bg-red-100 transition-colors"
                                                title="Delete Forever">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.brand.edit', $brand->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-[#8f4da7] hover:text-[#7a3d92] rounded-lg hover:bg-purple-100 transition-colors"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z"/>
                                            </svg>
                                        </a>
                                        <button wire:click="deleteBrand({{ $brand->id }})" 
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 rounded-lg hover:bg-red-100 transition-colors"
                                                title="Delete">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V9a4 4 0 01-4 4H9a4 4 0 01-4-4V5"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-[#171717] mb-1">
                                        @if($showDeleted)
                                            No deleted brands
                                        @else
                                            No brands found
                                        @endif
                                    </h3>
                                    <p class="text-gray-500">
                                        @if($showDeleted)
                                            No brands have been deleted yet.
                                        @elseif($search)
                                            Try adjusting your search terms.
                                        @else
                                            Get started by creating your first brand.
                                        @endif
                                    </p>
                                    @if(!$showDeleted && !$search)
                                        <a href="{{ route('admin.brand-add') }}" 
                                           class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-[#8f4da7] text-white hover:bg-[#7a3d92] transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Create First Brand
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($brands->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $brands->links('pagination.custom') }}
            </div>
        @endif
    </div>

    <!-- Include Confirmation Modal -->
    <livewire:components.confirmation-modal />
</div>