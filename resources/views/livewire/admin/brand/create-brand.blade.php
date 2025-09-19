<div class="w-full lg:w-2/3 mx-auto mb-5">
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-xl font-medium text-[#171717]">
                {{ $editingBrandId ? 'Edit Brand' : 'Create New Brand' }}
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form wire:submit.prevent="saveBrand" class="space-y-6">
                <!-- Brand Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Brand Name*</label>
                    <input type="text" id="name" wire:model.blur="name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"
                        required>
                    @error('name') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug*</label>
                    <input type="text" id="slug" wire:model.live="slug"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"
                        readonly required>
                    @error('slug') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand Logo</label>

                    <!-- Upload Progress -->
                    <div wire:loading wire:target="logo" class="my-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-[#8f4da7] h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <span class="text-sm text-gray-500 mt-1">Uploading...</span>
                    </div>

                    <div class="mt-2 flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#8f4da7] transition-colors">
                        <div class="space-y-2 text-center">
                            @if ($logo)
                                @if ($logo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                    <img src="{{ $logo->temporaryUrl() }}" class="mx-auto h-32 w-auto object-contain mb-4 rounded-lg shadow-sm">
                                    <button type="button" wire:click="removeImage"
                                        class="text-sm text-red-600 hover:text-red-800 transition-colors">
                                        Remove Image
                                    </button>
                                @endif
                            @elseif ($imagePreview)
                                <img src="{{ $imagePreview }}" class="mx-auto h-32 w-auto object-contain mb-4 rounded-lg shadow-sm">
                                <button type="button" wire:click="removeImage"
                                    class="text-sm text-red-600 hover:text-red-800 transition-colors">
                                    Remove Image
                                </button>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center flex-wrap">
                                    <label for="logo"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-[#8f4da7] hover:text-[#7a3d92] transition-colors">
                                        <span>Upload a file</span>
                                        <input id="logo" wire:model="logo" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            @endif
                        </div>
                    </div>
                    @error('logo') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
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
                    @error('description') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- SEO Section -->
                <div class="pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-medium text-[#171717] mb-4">SEO Settings</h3>

                    <div class="mb-4">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" id="meta_title" wire:model="meta_title"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors">
                        @error('meta_title') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea id="meta_description" wire:model="meta_description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-[#8f4da7] transition-colors"></textarea>
                        @error('meta_description') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col-reverse sm:flex-row justify-between items-center gap-4 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.brand.manage') }}" 
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 transition-colors text-sm font-medium">
                        ← Back to Brands
                    </a>
                    
                    <div class="flex gap-3 w-full sm:w-auto">
                        @if ($editingBrandId)
                            <button type="button" wire:click="resetForm"
                                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 transition-colors font-medium">
                                Cancel
                            </button>
                        @endif
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#8f4da7] text-white rounded-lg shadow-sm hover:bg-[#7a3d92] transition-colors font-medium">
                            {{ $editingBrandId ? 'Update Brand' : 'Create Brand' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>