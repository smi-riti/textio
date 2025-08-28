<div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-900">Update Category</h2>
        <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>
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

    <form wire:submit.prevent="save" class="space-y-6 bg-white p-6 rounded-lg border border-gray-200">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" wire:model="title" id="title"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" wire:model="slug" id="slug" disabled
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 bg-gray-50 sm:text-sm">
        </div>

        <div>
            <label for="parent_category_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
            <select wire:model="parent_category_id" id="parent_category_id"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">None</option>
                @foreach($parentCategories as $parentCategory)
                    <option value="{{ $parentCategory->id }}">{{ $parentCategory->title }}</option>
                @endforeach
            </select>
            @error('parent_category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Category Image</label>
            <div class="mt-1 flex items-center space-x-4">
                @if($category->image)
                    <div class="relative group">
                        <img src="{{ Storage::url($category->image) }}" class="h-32 w-32 object-cover rounded-lg">
                        <button type="button" wire:click="deleteImage" 
                                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 rounded-lg transition-opacity">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endif
                
                <div class="flex items-center justify-center w-full">
                    <label for="newImage" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-indigo-500 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            @if ($newImage)
                                <img src="{{ $newImage->temporaryUrl() }}" class="h-24 w-auto object-cover">
                            @else
                                <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            @endif
                        </div>
                        <input id="newImage" wire:model="newImage" type="file" class="hidden" accept="image/*" />
                    </label>
                </div>
            </div>
            @error('newImage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea wire:model="description" id="description" rows="4"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model="is_active" id="is_active"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
            @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
            <input type="text" wire:model="meta_title" id="meta_title"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('meta_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
            <textarea wire:model="meta_description" id="meta_description" rows="4"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            @error('meta_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Update Category
            </button>
        </div>
    </form>
</div>