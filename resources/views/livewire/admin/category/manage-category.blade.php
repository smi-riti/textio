<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Form Column -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-medium text-[#171717]">
                        {{ $editingCategoryId ? 'Edit Category' : 'Create New Category' }}
                    </h2>
                </div>
                <div class="p-6">
                    <form wire:submit.prevent="saveCategory" class="space-y-4">
                        <!-- Category Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Category Title*</label>
                            <input type="text" id="title" wire:model.blur="title" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors" 
                                required>
                            @error('title') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <label for="parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
                            <select id="parent_category_id" wire:model="parent_category_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors">
                                <option value="">Select Parent (Optional)</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                                @endforeach
                            </select>
                            @error('parent_category_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                            <label for="image" class="flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#8f4da7] transition-colors cursor-pointer">
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                    </svg>
                                    <span class="text-sm text-gray-500">Click to upload image</span>
                                </div>
                                <input id="image" type="file" wire:model="image" class="hidden" accept="image/*">
                            </label>
                            
                            @if ($imagePreview)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-500 mb-2">Image Preview:</p>
                                    <img src="{{ $imagePreview }}" alt="Image Preview" class="w-24 h-24 object-cover rounded-lg border border-gray-200 shadow-sm">
                                </div>
                            @elseif ($image)
                                <div wire:loading wire:target="image" class="mt-3 text-sm text-[#8f4da7]">
                                    Uploading...
                                </div>
                            @endif
                            @error('image') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status Toggle -->
                        <div class="flex items-center">
                            <input id="is_active" type="checkbox" wire:model="is_active" 
                                class="h-4 w-4 text-[#8f4da7] border-gray-300 rounded focus:ring-[#8f4da7] transition-colors">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active Status</label>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" wire:model="description" rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"></textarea>
                            @error('description') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- SEO Section -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-[#171717] mb-3">SEO Settings</h3>
                            <!-- Meta Title -->
                            <div class="mb-4">
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                <input type="text" id="meta_title" wire:model="meta_title" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors">
                                @error('meta_title') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>
                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                <textarea id="meta_description" wire:model="meta_description" rows="3" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"></textarea>
                                @error('meta_description') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4">
                            @if ($editingCategoryId)
                                <button type="button" wire:click="resetForm" 
                                    class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 transition-colors font-medium">
                                    Cancel
                                </button>
                            @endif
                            <button type="submit" 
                                class="px-5 py-2.5 bg-[#8f4da7] text-white rounded-lg shadow-sm hover:bg-[#7a3d92] transition-colors font-medium">
                                {{ $editingCategoryId ? 'Update Category' : 'Create Category' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Category List Column -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-medium text-[#171717]">Category Management</h2>
                    <div class="flex items-center">
                        <label for="showDeleted" class="text-sm font-medium text-gray-700 mr-3">Show Deleted</label>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none">
                            <input type="checkbox" id="showDeleted" wire:model="showDeleted" 
                                class="sr-only toggle-checkbox"/>
                            <label for="showDeleted" class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer">
                                <span class="toggle-dot absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full transition-transform duration-200 ease-in-out"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Image</th>
                                    <th scope="col" class="px-4 py-3">Title</th>
                                    <th scope="col" class="px-4 py-3">Parent</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($categories as $category)
                                    <tr class="hover:bg-gray-50 transition-colors {{ $category->trashed() ? 'bg-red-50' : ($category->is_active ? '' : 'bg-yellow-50') }}">
                                        <!-- Image -->
                                        <td class="px-4 py-3">
                                            @if ($category->image)
                                                <img src="{{ $category->image }}" alt="{{ $category->title }}" 
                                                    class="w-10 h-10 object-cover rounded-lg border border-gray-200 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <!-- Title with indentation -->
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                @if($category->level > 0)
                                                    @for($i = 0; $i < $category->level; $i++)
                                                        <span class="inline-block w-4 border-l border-gray-300 mr-1" style="height: 1.5rem;"></span>
                                                    @endfor
                                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                                <span class="{{ $category->trashed() ? 'line-through text-gray-500' : 'text-[#171717]' }}">{{ $category->title }}</span>
                                            </div>
                                        </td>
                                        <!-- Parent -->
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $category->parent ? $category->parent->title : '-' }}
                                        </td>
                                        <!-- Status -->
                                        <td class="px-4 py-3">
                                            @if($category->trashed())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Deleted
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            @endif
                                        </td>
                                        <!-- Actions -->
                                        <td class="px-4 py-3">
                                            <div class="flex justify-end items-center space-x-2">
                                                @if ($category->trashed())
                                                    <button wire:click="restoreCategory({{ $category->id }})" 
                                                        class="p-2 text-green-600 hover:text-green-800 rounded-lg hover:bg-green-100 transition-colors"
                                                        title="Restore">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button wire:click="editCategory({{ $category->id }})" 
                                                        class="p-2 text-[#8f4da7] hover:text-[#7a3d92] rounded-lg hover:bg-purple-100 transition-colors"
                                                        title="Edit">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="deleteCategory({{ $category->id }})" 
                                                        class="p-2 text-red-600 hover:text-red-800 rounded-lg hover:bg-red-100 transition-colors"
                                                        title="Delete">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V9a4 4 0 01-4 4H9a4 4 0 01-4-4V5"/>
                                                </svg>
                                                <h3 class="text-lg font-medium text-[#171717] mb-1">No categories found</h3>
                                                <p class="text-gray-500">Get started by creating your first category</p>
                                            </div>
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

<style>
.toggle-checkbox:checked ~ label {
    background-color: #8f4da7;
}
.toggle-checkbox:checked ~ label .toggle-dot {
    transform: translateX(1.25rem);
}
</style>