<div class="container mx-auto px-4 py-8">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Category Form Column -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border shadow-md overflow-hidden">
                <div class="px-6 py-4">
                    <h2 class="text-xl font-semibold">
                        {{ $editingCategoryId ? 'Edit Category' : 'Create New Category' }}
                    </h2>
                </div>
                <div class="p-6">
                    <form wire:submit.prevent="saveCategory">
                        <!-- Title -->
                        <div class="mb-5">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Category Title*</label>
                            <input type="text" id="title" wire:model.blur="title" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            @error('title') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-5">
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug*</label>
                            <input type="text" id="slug" wire:model.live="slug" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            @error('slug') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Parent Category -->
                        <div class="mb-5">
                            <label for="parent_category_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                            <select id="parent_category_id" wire:model="parent_category_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">Select Parent (Optional)</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                                @endforeach
                            </select>
                            @error('parent_category_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>                       

                        <!-- Image Upload -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                            <div class="mt-1 flex items-center">
                                <label for="image" class="cursor-pointer">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-400 transition-colors duration-200">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">Click to upload image</span>
                                        </div>
                                        <input id="image" type="file" wire:model="image" class="hidden">
                                    </div>
                                </label>
                            </div>
                            @if ($imagePreview)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-500 mb-1">Image Preview:</p>
                                    <img src="{{ $imagePreview }}" alt="Preview" class="h-24 w-24 object-cover rounded-md border">
                                </div>
                            @elseif ($image)
                                <div wire:loading wire:target="image" class="mt-3 text-sm text-blue-600">
                                    Uploading...
                                </div>
                            @endif
                            @error('image') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status Toggle -->
                        <div class="mb-5">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_active" wire:model="is_active" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-700">Active Status</span>
                            </label>
                        </div>

                        <!-- Description -->
                        <div class="mb-5">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" wire:model="description" rows="3" 
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"></textarea>
                            @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- SEO Section -->
                        <div class="border-t border-gray-200 pt-5 mb-5">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">SEO Settings</h3>
                            
                            <!-- Meta Title -->
                            <div class="mb-4">
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input type="text" id="meta_title" wire:model="meta_title" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                @error('meta_title') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea id="meta_description" wire:model="meta_description" rows="3" 
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"></textarea>
                                @error('meta_description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-4">
                            @if ($editingCategoryId)
                                <button type="button" wire:click="resetForm" 
                                        class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                    Cancel
                                </button>
                            @endif
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                {{ $editingCategoryId ? 'Update Category' : 'Create Category' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Category List Column -->
        <div class="lg:col-span-2 ">
            <div class="bg-white rounded-xl border shadow-md overflow-hidden">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Category Management</h2>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="showDeleted" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium ">Show Deleted</span>
                    </label>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                                  
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white  divide-y divide-gray-200">
                                @forelse ($categories as $category)
                                    <tr class="{{ $category->trashed() ? 'bg-rose-50' : ($category->is_active ? '' : 'bg-amber-50') }}">
                                        <!-- Image -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($category->image)
                                                <img src="{{ $category->image }}" alt="{{ $category->title }}" class="h-10 w-10 object-cover rounded-md">
                                            @else
                                                <div class="h-10 w-10 flex items-center justify-center bg-gray-100 rounded-md text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <!-- Title with indentation based on level -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($category->level > 0)
                                                    @for($i = 0; $i < $category->level; $i++)
                                                        <span class="inline-block w-4"></span>
                                                    @endfor
                                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                @endif
                                                <span class="{{ $category->trashed() ? 'line-through text-gray-500' : 'text-gray-900' }}">{{ $category->title }}</span>
                                            </div>
                                        </td>
                                        
                                        <!-- Parent -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $category->parent ? $category->parent->title : '-' }}
                                        </td>
                                        
                                      
                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($category->trashed())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-rose-100 text-rose-800">
                                                    Deleted
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-3">
                                                @if ($category->trashed())
                                                    <button wire:click="restoreCategory({{ $category->id }})" 
                                                            class="text-emerald-600 hover:text-emerald-900" 
                                                            title="Restore">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button wire:click="editCategory({{ $category->id }})" 
                                                            class="text-blue-600 hover:text-blue-900" 
                                                            title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="deleteCategory({{ $category->id }})" 
                                                            class="text-rose-600 hover:text-rose-900" 
                                                            title="Delete">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No categories found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                                  
                </div>
            </div>
        </div>
    </div>
</div>