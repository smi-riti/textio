<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="border border-purple-200 rounded-2xl bg-white shadow-sm">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-purple-200 bg-purple-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-purple-800">Edit Brand</h2>
                    <p class="text-sm text-purple-600 mt-1">Update brand information and settings</p>
                </div>
                <button wire:click="cancel" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-purple-300 text-purple-700 bg-white hover:bg-purple-50 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Back to Brands
                </button>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('message'))
            <div class="mx-6 mt-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Form -->
        <form wire:submit.prevent="updateBrand" class="p-6 space-y-6">
            <!-- Brand Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Brand Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name"
                       wire:model.blur="name" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                       placeholder="Enter brand name">
                @error('name')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea id="description"
                          wire:model="description" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('description') border-red-500 ring-2 ring-red-200 @enderror"
                          placeholder="Enter brand description (optional)"></textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Current Logo & Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Brand Logo
                </label>
                
                <!-- Current Logo Display -->
                @if($existing_logo)
                    <div class="mb-4 p-4 border border-purple-200 rounded-lg bg-purple-25">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $existing_logo }}" 
                                     alt="Current Logo" 
                                     class="w-16 h-16 rounded-lg object-contain bg-white p-2 border border-purple-200 shadow-sm">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Current Logo</p>
                                    <p class="text-xs text-gray-500">Click remove to delete this logo</p>
                                </div>
                            </div>
                            <button type="button" 
                                    wire:click="removeLogo"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-red-300 text-red-700 bg-white hover:bg-red-50 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"/>
                                </svg>
                                Remove
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Upload New Logo -->
                <div class="border-2 border-dashed border-purple-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors duration-200 @error('logo') border-red-300 @enderror">
                    <div class="space-y-4">
                        <div class="mx-auto w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div>
                            <label for="logo" class="cursor-pointer">
                                <span class="text-sm font-medium text-purple-600 hover:text-purple-700">
                                    {{ $existing_logo ? 'Upload New Logo' : 'Upload Logo' }}
                                </span>
                                <input type="file" 
                                       id="logo"
                                       wire:model="logo" 
                                       accept="image/*"
                                       class="sr-only">
                            </label>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                </div>

                @error('logo')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror

                <!-- Preview New Upload -->
                @if($logo)
                    <div class="mt-4 p-4 border border-green-200 rounded-lg bg-green-25">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $logo->temporaryUrl() }}" 
                                 alt="New Logo Preview" 
                                 class="w-16 h-16 rounded-lg object-contain bg-white p-2 border border-green-200 shadow-sm">
                            <div>
                                <p class="text-sm font-medium text-gray-900">New Logo Preview</p>
                                <p class="text-xs text-gray-500">This will replace the current logo when saved</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Status
                </label>
                <div class="flex items-center space-x-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               wire:model="is_active"
                               class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 focus:ring-offset-2">
                        <span class="ml-2 text-sm text-gray-700">
                            Active (Brand will be visible to customers)
                        </span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    Inactive brands will not be shown in product listings or search results
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <button type="button" 
                        wire:click="cancel"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200">
                    Cancel
                </button>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 text-sm font-medium rounded-lg bg-purple-600 text-white hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Update Brand
                </button>
            </div>
        </form>
    </div>
</div>