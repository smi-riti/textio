<div class="min-h-screen bg-gray-50">
    <!-- Stepper -->
    <livewire:admin.product.product-stepper :currentStep="$currentStep" :completedSteps="$completedSteps" />

    <div class="p-4 lg:p-8">
        <div class="mx-auto max-w-4xl">
            <!-- Header -->
            <div class="mb-8 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div>
                    <h1 class="text-2xl font-medium text-gray-900">Create Product</h1>
                    <p class="mt-1 text-gray-600">Add a new custom printing product to your catalog</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.index') }}"
                        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 transition-colors duration-200">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Products
                    </a>
                </div>
            </div>

            <!-- Loading Overlay -->
            @if ($isSaving)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl p-6 shadow-lg max-w-md mx-auto">
                        <div class="flex items-center">
                            <svg class="animate-spin h-5 w-5 text-[#8f4da7] mr-3" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="text-gray-700">{{ $loadingMessage }}</span>
                        </div>
                    </div>
                </div>
            @endif

           @if ($currentStep === 1)
    <div class="rounded-xl bg-white p-4 lg:p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-medium text-gray-900">Basic Information</h2>
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" wire:model.live="name" placeholder="Enter product name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                <input type="text" wire:model="slug" placeholder="product-slug"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Used in product URL</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select wire:model="category_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                <select wire:model="brand_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                    <option value="">Select Brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                <input type="text" wire:model="weight" placeholder="Enter weight"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                @error('weight')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Length (cm)</label>
                <input type="text" wire:model="length" placeholder="Enter length"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                @error('length')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Breadth (cm)</label>
                <input type="text" wire:model="breadth" placeholder="Enter breadth"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                @error('breadth')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                <input type="text" wire:model="height" placeholder="Enter height"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                @error('height')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea wire:model="description" rows="4" placeholder="Describe your product..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent resize-none transition-colors duration-200"></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <!-- Customization moved to step 4 -->
        </div>
    </div>
@endif

            @if ($currentStep === 2)
                <div class="rounded-xl bg-white p-4 lg:p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-medium text-gray-900">Pricing</h2>
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price (MRP) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                <input type="number" step="0.01" wire:model.live="price" placeholder="0.00"
                                    class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selling Price *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                <input type="number" step="0.01" wire:model.live="discount_price"
                                    placeholder="0.00"
                                    class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Less than regular price</p>
                            @error('discount_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @if ($price && $discount_price && $price > $discount_price)
                        <div class="mt-5 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-green-700">Discount:
                                    {{ number_format((1 - $discount_price / $price) * 100, 2) }}%</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($currentStep === 3)
                <div class=" p-2 bg-white lg:p-4  rounded-xl ">
                    <h2 class="mb-5 text-lg font-medium text-gray-900">Variants & Images</h2>
                    <p class="text-gray-600 mb-4">At least one variant combination is required (e.g., Color: Red, Size:
                        XL).</p>
                    @error('variantCombinations')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <livewire:admin.product.product-variants :product :variantCombinations="$variantCombinations" :isEdit="false"
                        wire:ignore.self />
                </div>
            @endif

            @if ($currentStep === 4)
                <form id="submit-form" wire:submit="save" class="space-y-6">
                    <div class="rounded-xl bg-white p-4 lg:p-6 shadow-sm">
                        <h2 class="mb-5 text-lg font-medium text-gray-900">Product Highlights</h2>
                        <div class="space-y-3">
                            @foreach ($highlights as $index => $highlight)
                                <div class="flex items-center space-x-2">
                                    <input type="text" value="{{ $highlight }}" readonly
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                    <button type="button" wire:click="removeHighlight({{ $index }})"
                                        class="p-2 text-red-500 hover:text-red-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <div class="flex space-x-2">
                                <input type="text" wire:model="new_highlight" placeholder="Add highlight..."
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] transition-colors duration-200">
                                <button type="button" wire:click="addHighlight"
                                    class="px-4 py-2 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a4190] transition-colors duration-200">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl bg-white p-4 lg:p-6 shadow-sm">
                        <h2 class="mb-5 text-lg font-medium text-gray-900">SEO & Settings</h2>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                <input type="text" wire:model="meta_title" placeholder="SEO title"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] transition-colors duration-200">
                                @error('meta_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                <textarea wire:model="meta_description" rows="3" placeholder="SEO description"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] resize-none transition-colors duration-200"></textarea>
                                @error('meta_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="status" id="status"
                                        class="w-4 h-4 text-[#8f4da7] bg-gray-100 border-gray-300 rounded focus:ring-[#8f4da7]">
                                    <label for="status"
                                        class="ml-3 text-sm text-gray-700 cursor-pointer">Active</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="featured" id="featured"
                                        class="w-4 h-4 text-[#8f4da7] bg-gray-100 border-gray-300 rounded focus:ring-[#8f4da7]">
                                    <label for="featured"
                                        class="ml-3 text-sm text-gray-700 cursor-pointer">Featured on
                                        homepage</label>
                                </div>
                                <div class="flex items-center mt-3">
                                    <input type="checkbox" wire:model="is_customizable" id="is_customizable"
                                        class="w-4 h-4 text-[#8f4da7] bg-gray-100 border-gray-300 rounded focus:ring-[#8f4da7] focus:ring-2">
                                    <label for="is_customizable"
                                        class="ml-3 text-sm text-gray-700 cursor-pointer">This product can be customized </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif

            <!-- Navigation -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
                @if ($currentStep > 1)
                    <button type="button" wire:click="previousStep"
                        class="inline-flex items-center px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Previous
                    </button>
                @else
                    <div></div> <!-- Empty div to maintain flex spacing -->
                @endif

                @if ($currentStep < 4)
                    <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                        class="inline-flex items-center px-5 py-2.5 text-sm text-white bg-[#8f4da7] rounded-lg shadow-sm hover:bg-[#7a4190] transition-colors duration-200">
                        Next Step
                        <svg wire:loading class="animate-spin w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                @else
                    <button type="submit" form="submit-form" wire:loading.attr="disabled"
                        class="inline-flex items-center px-5 py-2.5 text-sm text-white bg-[#8f4da7] rounded-lg shadow-sm hover:bg-[#7a4190] transition-colors duration-200">
                        Create Product
                        <svg wire:loading class="animate-spin w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>