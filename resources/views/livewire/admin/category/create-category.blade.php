
<div class="container mx-auto px-4 py-8">
<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Create Category</h2>

    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" wire:model.blur="title" id="title"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="parent_category_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
            <select wire:model.blur="parent_category_id" id="parent_category_id"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">None</option>
                @foreach($parentCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
            @error('parent_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
                {{-- <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Category Image</label>
            <div class="mt-1 p-2 flex items-center justify-center px-6 pt-5 pb-6 border border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                <div class="space-y-1 text-center">
                    @if ($image)
                        @if ($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                            <img src="{{ $image->temporaryUrl() }}" class="mx-auto h-32 w-auto object-cover mb-4">
                        @endif
                    @else
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    @endif
                    <div class="flex text-sm text-gray-600">
                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Upload a file</span>
                            <input id="image" wire:model="image" type="file" class="sr-only" accept="image/*">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                </div>
            </div>
            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div> --}}

            <!-- Upload Progress -->
         <div class="mb-4">
    <label for="image" class="block text-sm font-medium text-gray-700">
        Category Image <span class="text-red-500">*</span>
    </label>
    
    <!-- Upload Progress -->
    <div wire:loading wire:target="image" class="my-2">
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 0%" wire:loading wire:target="image"></div>
        </div>
        <span class="text-sm text-gray-500">Uploading...</span>
    </div>
    
    <div class="mt-1 p-2 flex items-center justify-center px-6 pt-5 pb-6 border border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors">
        <div class="space-y-1 text-center">
            @if ($image)
                @if ($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                    <img src="{{ $image->temporaryUrl() }}" class="mx-auto h-32 w-auto object-cover mb-4 rounded-md">
                    <button type="button" wire:click="removeImage" class="text-sm text-red-600 hover:text-red-800">
                        Remove Image
                    </button>
                @endif
            @elseif ($imageUrl)
                <img src="{{ $imageUrl }}" class="mx-auto h-32 w-auto object-cover mb-4 rounded-md">
                <button type="button" wire:click="removeImage" class="text-sm text-red-600 hover:text-red-800">
                    Remove Image
                </button>
            @else
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            @endif
            
            @if (!$image && !$imageUrl)
            <div class="flex text-sm text-gray-600 justify-center">
                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <span>Upload a file</span>
                    <input id="image" wire:model="image" type="file" class="sr-only" accept="image/*">
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
            @endif
        </div>
    </div>
    @error('image') <p class="mt-1 text-sm text-red-c600">{{ $message }}</p> @enderror
</div>
        
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea wire:model.blur="description" id="description" rows="4"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model.blur="is_active"
                    class="rounded border border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
            @error('is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
            <input type="text" wire:model.blur="meta_title" id="meta_title"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            @error('meta_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
            <textarea wire:model.blur="meta_description" id="meta_description" rows="4"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            @error('meta_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-between items-center ">
              <a href="{{ route('admin.categories.index') }}" 
           class="px-4 py-2 bg-gray-300 text-gray-700  hover:text-white rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
            Back to Categories
        </a>
            <button type="submit" wire:loading.attr="disabled"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save">Save</span>
                <span wire:loading wire:target="save" class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>
</div>