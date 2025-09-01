

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="border border-orange-200 rounded-lg bg-white">

        <!-- Header -->
<div class="flex justify-between items-center px-6 py-4 border-b border-orange-200 bg-orange-50">
    <h2 class="text-lg font-semibold text-orange-700">Brand Management</h2>

    <div class="flex items-center space-x-4">
        <!-- Tabs -->
        <div class="flex space-x-2">
            <button 
                wire:click="showList" 
                class="px-3 py-1.5 text-sm font-medium rounded 
                       {{ !$showDeleted ? 'bg-orange-600 text-white' : 'bg-orange-100 text-orange-700 hover:bg-orange-200' }}">
                List
            </button>
            <button 
                wire:click="showTrash" 
                class="px-3 py-1.5 text-sm font-medium rounded 
                       {{ $showDeleted ? 'bg-orange-600 text-white' : 'bg-orange-100 text-orange-700 hover:bg-orange-200' }}">
                Trash
            </button>
        </div>

        <!-- Create button -->
        @if(!$showDeleted)
            <a href="{{ route('admin.brand-add') }}" 
               class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded bg-orange-600 text-white hover:bg-orange-700">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create New Brand
            </a>
        @endif
    </div>
</div>

 @if(session('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-orange-100 text-orange-800">
                    <tr>
                        <th class="pl-6 pr-3 py-3 font-semibold">Logo</th>
                        <th class="px-3 py-3 font-semibold">Name</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="pl-3 pr-6 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-100">
                    @forelse ($brands as $brand)
                        <tr class="@if($brand->trashed()) bg-red-50 @elseif(!$brand->is_active) bg-yellow-50 @else bg-white @endif">
                        <!-- Logo -->
<td class="pl-6 pr-3 py-3">
    @if ($brand->logo)
        <img src="{{ $brand->logo }}"  {{-- Directly use the ImageKit URL --}}
             alt="{{ $brand->name }}" 
             class="w-10 h-10 rounded object-contain bg-white p-1 border border-orange-200">
    @else
        <div class="w-10 h-10 rounded bg-orange-100 flex items-center justify-center border border-orange-200">
            <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
            </svg>
        </div>
    @endif
</td>
                            
                            <!-- Name -->
                            <td class="px-3 py-3 font-medium text-gray-800">{{ $brand->name }}</td>
                            
                            <!-- Status -->
                            <td class="px-3 py-3">
                                @if($brand->trashed())
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Deleted</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded {{ $brand->is_active ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="pl-3 pr-6 py-3">
                                <div class="flex justify-end space-x-2">
                                    @if ($brand->trashed())
                                        <button wire:click="restoreBrand({{ $brand->id }})" 
                                                class="px-2 py-1 text-xs font-medium rounded border border-green-500 text-green-600 hover:bg-green-50" 
                                                title="Restore">
                                            Restore
                                        </button>
                                    @else
                                        <button wire:click="editBrand({{ $brand->id }})" 
                                                class="px-2 py-1 text-xs font-medium rounded border border-blue-500 text-blue-600 hover:bg-blue-50" 
                                                title="Edit">
                                            Edit
                                        </button>
                                        <button wire:click="deleteBrand({{ $brand->id }})" 
                                                class="px-2 py-1 text-xs font-medium rounded border border-red-500 text-red-600 hover:bg-red-50" 
                                                title="Delete">
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-orange-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm">No brands found</p>
                                    <a href="{{ route('admin.brand.manage') }}" 
                                       class="mt-2 inline-flex items-center px-3 py-1.5 text-sm font-medium rounded bg-orange-600 text-white hover:bg-orange-700">
                                        Create Your First Brand
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($brands->hasPages())
            <div class="px-6 py-4 border-t border-orange-200">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</div>
