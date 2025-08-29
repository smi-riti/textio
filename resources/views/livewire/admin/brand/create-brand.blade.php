<div class="w-full lg:w-2/3 mx-auto mb-5">
    <div class="bg-white rounded-xl  border border-gray-200">
        <!-- Card Header -->
        <div class="px-4 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $editingBrandId ? 'Edit Brand' : 'Create New Brand' }}
            </h2>
        </div>


        <!-- Card Body -->
        <div class="p-6">
            <form wire:submit.prevent="saveBrand" class="space-y-4">
                <!-- Brand Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Brand Name*</label>
                    <input type="text" id="name" wire:model.blur="name"
                        class="mt-1 block w-full rounded-md border  border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required>
                    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug*</label>
                    <input type="text" id="slug" wire:model.live="slug"
                        class="mt-1 block w-full rounded-md border  border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        readonly required>
                    @error('slug') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Brand Logo</label>

                    <!-- Upload Progress -->
                    <div wire:loading wire:target="logo" class="my-2">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <span class="text-sm text-gray-500">Uploading...</span>
                    </div>

                    <div
                        class="mt-1 flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-400 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                        <div class="space-y-1 text-center">
                            @if ($logo)
                                @if ($logo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                    <img src="{{ $logo->temporaryUrl() }}" class="mx-auto h-32 w-auto object-cover mb-4 rounded-md">
                                    <button type="button" wire:click="removeImage"
                                        class="text-sm text-red-600 hover:text-red-800">
                                        Remove Image
                                    </button>
                                @endif
                            @elseif ($imagePreview)
                                <img src="{{ $imagePreview }}" class="mx-auto h-32 w-auto object-cover mb-4 rounded-md">
                                <button type="button" wire:click="removeImage"
                                    class="text-sm text-red-600 hover:text-red-800">
                                    Remove Image
                                </button>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="logo"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="logo" wire:model="logo" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            @endif
                        </div>
                    </div>
                    @error('logo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status Toggle -->
                <div class="flex items-center">
                    <input id="is_active" type="checkbox" wire:model="is_active"
                        class="h-4 w-4 text-indigo-600 border border-gray-400 rounded focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active Status</label>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" wire:model="description" rows="3"
                        class="mt-1 block w-full rounded-md border border-gray-400 border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- SEO Section -->
                <div class="pt-4 border-t border-gray-200">
                    <h3 class="text-md font-medium text-gray-800 mb-3">SEO Settings</h3>

                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                        <input type="text" id="meta_title" wire:model="meta_title"
                            class="mt-1 block w-full rounded-md border  border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('meta_title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="meta_description"
                            class="block text-sm mt-4 font-medium text-gray-700">Meta Description</label>
                        <textarea id="meta_description" wire:model="meta_description" rows="3"
                            class="mt-1 block w-full rounded-md border  border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        @error('meta_description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('admin.brand.manage') }}" 
       class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-400 hover:text-white  text-sm">
        ← Back
    </a>
    <div class="flex justify-end gap-2 pt-3">
                    @if ($editingBrandId)
                        <button type="button" wire:click="resetForm"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </button>
                    @endif
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $editingBrandId ? 'Update Brand' : 'Create Brand' }}
                    </button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
