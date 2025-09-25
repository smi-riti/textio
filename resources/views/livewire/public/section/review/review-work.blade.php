<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 sm:px-6 py-4 bg-white border-b border-gray-200">
        <h1 class="text-xl font-medium text-[#171717] mb-2 sm:mb-0">Rating and Reviews</h1>
        <a wire:navigate href="{{ route('view.product', $product->slug) }}"
            class="text-sm text-[#8f4da7] hover:text-[#171717] transition-colors truncate max-w-xs sm:max-w-md font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2 text-xs"></i>
            {{ $product->name }}
        </a>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col lg:flex-row p-4 sm:p-6 gap-6">
        <!-- Left Column - Guidelines (Hidden on mobile) -->
        <div class="hidden lg:block w-full lg:w-4/12 bg-white rounded-lg border border-gray-200 p-6">
            <h1 class="text-lg font-medium text-[#171717] mb-4">What makes a good review</h1>
            <hr class="mb-4 border-gray-200">

            <div class="mb-4">
                <div class="flex items-start mb-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-check text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-medium text-gray-700 mb-1">Have you used this product?</h2>
                        <p class="text-gray-600 text-sm">Your review should be about your experience with the product.</p>
                    </div>
                </div>
            </div>
            <hr class="mb-4 border-gray-200">

            <div class="mb-4">
                <div class="flex items-start mb-2">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-users text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-medium text-gray-700 mb-1">Why review a product?</h2>
                        <p class="text-gray-600 text-sm">Your valuable feedback will help fellow shoppers decide!</p>
                    </div>
                </div>
            </div>
            <hr class="mb-4 border-gray-200">

            <div class="mb-4">
                <div class="flex items-start mb-2">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-lightbulb text-[#8f4da7] text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-medium text-gray-700 mb-1">How to review a product?</h2>
                        <p class="text-gray-600 text-sm">Your review should include facts. An honest opinion is always appreciated.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-6 p-3 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center">
                    <i class="fas fa-headset text-[#8f4da7] mr-2"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Need help with the product?</p>
                        <a href="#" class="text-sm text-[#8f4da7] hover:underline">Contact our support team</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Guidelines Toggle -->
        <div class="lg:hidden bg-white rounded-lg border border-gray-200 p-4 mb-4">
            <button onclick="toggleGuidelines()" 
                    class="w-full flex items-center justify-between text-left font-medium text-[#171717]">
                <span>Review Guidelines</span>
                <i class="fas fa-chevron-down transition-transform duration-200" id="guidelines-arrow"></i>
            </button>
            <div id="mobile-guidelines" class="hidden mt-4 pt-4 border-t border-gray-200">
                <div class="space-y-4">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-check text-blue-600 mr-2 text-sm"></i>
                            Have you used this product?
                        </h3>
                        <p class="text-gray-600 text-sm">Your review should be about your experience with the product.</p>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-users text-green-600 mr-2 text-sm"></i>
                            Why review a product?
                        </h3>
                        <p class="text-gray-600 text-sm">Your valuable feedback will help fellow shoppers decide!</p>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-lightbulb text-[#8f4da7] mr-2 text-sm"></i>
                            How to review a product?
                        </h3>
                        <p class="text-gray-600 text-sm">Your review should include facts. An honest opinion is always appreciated.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Rating and Review Form -->
        <div class="w-full lg:w-8/12 bg-white rounded-lg border border-gray-200 p-4 sm:p-6" x-data="{ rating: 0 }">
            @if (session('message'))
                <div class="flex items-center bg-green-50 text-green-700 px-4 py-3 rounded-lg border border-green-200 mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save">
                <h2 class="text-lg font-medium text-[#171717] mb-4">Rate this product</h2>

                <!-- Star Rating -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Your Rating</h3>
                    <div class="flex space-x-1 mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    wire:click="$set('rating', {{ $i }})"
                                    class="text-2xl transition-transform duration-200 hover:scale-110
                                           {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}">
                                <i class="{{ $rating >= $i ? 'fas fa-star' : 'far fa-star' }}"></i>
                            </button>
                        @endfor
                    </div>

                    <!-- Rating Text -->
                    @if ($rating)
                        <p class="text-sm font-medium text-[#8f4da7] flex items-center">
                            <i class="fas 
                                {{ $rating >= 4 ? 'fa-smile-beam' : 
                                   ($rating >= 3 ? 'fa-smile' : 
                                   ($rating >= 2 ? 'fa-meh' : 'fa-frown')) }} 
                                mr-2">
                            </i>
                            @switch($rating)
                                @case(1)
                                    Very Bad
                                @break

                                @case(2)
                                    Bad
                                @break

                                @case(3)
                                    Good
                                @break

                                @case(4)
                                    Very Good
                                @break

                                @case(5)
                                    Excellent
                                @break
                            @endswitch
                        </p>
                    @endif

                    <!-- Validation Error -->
                    @error('rating')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="text-sm text-gray-500 mt-2">Click on the stars to rate</p>
                </div>

                <!-- Review Form -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Review this product</h3>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="review">
                            Your Review
                            <span class="text-gray-400 text-xs font-normal">(optional)</span>
                        </label>
                        <textarea id="review" 
                                  wire:model="review" 
                                  rows="5"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-colors duration-200"
                                  placeholder="Share details of your experience with the product. What did you like or dislike?"></textarea>
                        @error('review')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Character Counter -->
                    <div class="text-right text-sm text-gray-500">
                        {{ strlen($review ?? '') }}/500 characters
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <button type="submit"
                            class="bg-[#8f4da7] hover:bg-[#7a3d8f] text-white font-medium py-3 px-8 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Review
                    </button>
                    
                    <button type="button" 
                            wire:click="$dispatch('cancel')"
                            class="border border-gray-300 text-gray-700 font-medium py-3 px-8 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </form>

            <!-- Tips for Writing a Good Review -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-tips text-[#8f4da7] mr-2"></i>
                    Tips for writing a helpful review:
                </h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Focus on the product's features and your experience
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Be specific about what you liked or disliked
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                        Mention how long you've used the product
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <script>
    function toggleGuidelines() {
        const guidelines = document.getElementById('mobile-guidelines');
        const arrow = document.getElementById('guidelines-arrow');
        
        guidelines.classList.toggle('hidden');
        arrow.classList.toggle('fa-chevron-down');
        arrow.classList.toggle('fa-chevron-up');
    }
</script>

<style>
    .transition-colors {
        transition-property: color, background-color, border-color;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }

    .text-\[\#171717\] {
        color: #171717;
    }

    .text-\[\#8f4da7\] {
        color: #8f4da7;
    }

    .bg-\[\#8f4da7\] {
        background-color: #8f4da7;
    }

    .hover\:bg-\[\#7a3d8f\]:hover {
        background-color: #7a3d8f;
    }

    .focus\:ring-\[\#8f4da7\]:focus {
        --tw-ring-color: #8f4da7;
    }
</style>
</div>

