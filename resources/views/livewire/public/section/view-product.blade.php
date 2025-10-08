<div wire:ignore.self x-data="{ ...productPage(), buttonLoading: false }">
    <x-loader message="Please wait..." class="bg-[#8f4da7]" x-show="buttonLoading" />
    <style>
        .zoom-image {
            transition: transform 0.5s ease;
            cursor: zoom-in;
        }

        .zoom-image:hover {
            transform: scale(1.5);
        }

        .product-thumb {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .product-thumb:hover,
        .product-thumb.active {
            border-color: #8f4da7;
        }

        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #8f4da7;
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            z-index: 1000;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: #7a3c93;
        }

        .fixed-bottom-buttons {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 16px;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
            gap: 12px;
        }

        @media (max-width: 767px) {
            .fixed-bottom-buttons {
                display: flex;
            }

            .back-to-top {
                display: none;
            }

            .desktop-buttons {
                display: none;
            }
        }

        @media (min-width: 768px) {
            .desktop-buttons {
                display: flex;
                gap: 12px;
            }

            .fixed-bottom-buttons {
                display: none;
            }
        }

        .variant-button {
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .variant-button:hover:not(:disabled) {
            border-color: #8f4da7;
        }

        .variant-button.selected {
            border-color: #8f4da7;
            background-color: #f5f0ff;
        }

        .variant-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: #E5E7EB;
            background-color: #F9FAFB;
        }

        .tab-button {
            position: relative;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .tab-button::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 2px;
            background: #8f4da7;
            transition: width 0.3s ease;
        }

        .tab-button.active::after {
            width: 100%;
        }

        @media (max-width: 767px) {
            .product-images {
                flex-direction: column;
            }

            .thumbnails {
                flex-direction: row;
                order: 2;
                margin-top: 12px;
                overflow-x: auto;
                padding-bottom: 8px;
            }

            .thumbnails img {
                min-width: 60px;
                height: 60px;
            }

            .main-image {
                order: 1;
            }

            .variant-buttons {
                overflow-x: auto;
                padding-bottom: 8px;
            }

            .variant-button {
                min-width: max-content;
            }
        }

        .share-button {
            transition: background-color 0.3s ease;
        }

        .share-button:hover {
            background-color: #f5f0ff;
        }
    </style>

    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 py-6">
            @if (session('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Notification for variant selection -->
            <div wire:ignore x-show="notification.message" class="fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg"
                :class="notification.type === 'error' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'">
                <span x-text="notification.message"></span>
                <button @click="notification.message = ''" class="ml-2 text-lg">&times;</button>
            </div>

            <!-- Product Section -->
            <div class="flex flex-col lg:flex-row gap-8 mb-16">
                <!-- Product Images -->
                <div class="lg:w-1/2">
                    <div class="flex flex-col md:flex-row gap-6 product-images">
                        <!-- Thumbnails -->
                        <div class="flex md:flex-col gap-3 thumbnails">
                            <template x-for="(thumb, index) in thumbs" :key="index">
                                <img :src="thumb"
                                    class="w-16 h-16 object-cover product-thumb cursor-pointer rounded-lg"
                                    :class="{ 'active': activeImageIndex === index }" @click="setActiveImage(index)"
                                    alt="Thumbnail">
                            </template>
                        </div>

                        <!-- Main Image -->
                        <div class="relative main-image flex-1">
                            <div class="relative overflow-hidden gap-2 rounded-xl bg-gray-100">
                                <img x-bind:src="images[activeImageIndex]"
                                    class="w-full h-[30rem] object-contain zoom-image" alt="{{ $product->name }}"
                                    @mousemove="zoomImage($event)">

                                <!-- Fullscreen button -->
                                <div class="flex gap-2">
                                    <button
                                        class="absolute top-3 right-2 p-2 rounded-full bg-white shadow-md transition-colors hover:bg-gray-100 w-10 h-10 flex items-center justify-center"
                                        @click="openFullscreen()" title="View Fullscreen">
                                        <i class="fas fa-eye text-[#8f4da7] text-base"></i>
                                    </button>

                                    <!-- Wishlist button -->
                                    <div class="absolute top-3 right-14">
                                        <livewire:public.section.wishlist-button :productId="$product->id" />
                                    </div>


                                    {{-- <!-- Share button -->
                                <button
                                    class="absolute top-3 right-24 p-2 rounded-full bg-white shadow-md transition-colors hover:bg-gray-100 w-10 h-10 flex items-center justify-center"
                                    @click="shareProduct()" title="Share Product">
                                    <i class="fas fa-share-alt text-[#8f4da7] text-base"></i>
                                </button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="lg:w-1/2 relative">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-lg font-sans  text-gray-800 md:text-xl">
                            {{ $product->name }}
                        </h1>
                        <button
                            class="group flex items-center gap-2 px-3 py-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200"
                            @click="shareProduct()" title="Share Product" aria-label="Share Product">
                            <i class="fas fa-share-alt text-gray-500 text-sm "></i>
                            <span
                                class="text-sm font-medium text-gray-600 group-hover:text-gray-800 hidden sm:inline">Share</span>
                        </button>
                    </div>


                    <!-- Ratings -->
                    @if ($approvedReviews && $approvedReviews->isNotEmpty())
                        <div class="flex items-center gap-2 mb-5">
                            <a class="text-xs rounded px-2 py-1 bg-green-600 text-white">
                                {{ number_format($approvedReviews->avg('rating') ?? 0, 1) }}
                                <i class="fas fa-star text-white text-xs"></i>
                            </a>
                            <span class="text-gray-400 text-xs">{{ $approvedReviews->count() }}
                                Rating{{ $approvedReviews->count() === 1 ? '' : 's' }}</span>
                        </div>
                    @endif

                    <!-- Price -->
                    <div class="flex items-center gap-3 mb-6" wire:loading.class="opacity-50">
                        <p class="text-2xl font-semibold text-[#8f4da7]">
                            @if ($selectedVariantCombination)
                                ₹{{ number_format($price, 2) }}
                            @else
                                ₹{{ number_format($product->discount_price ?? $product->price, 2) }}
                            @endif
                        </p>
                        @if ($hasDiscount)
                            <p class="text-sm text-gray-400 line-through">₹{{ number_format($regularPrice, 2) }}</p>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $savingPercentage }}% Off
                            </span>
                        @elseif(!$selectedVariantCombination && $product->discount_price && $product->discount_price < $product->price)
                            @php
                                $productSaving = $product->price - $product->discount_price;
                                $productSavingPercentage = round(($productSaving / $product->price) * 100);
                            @endphp
                            <p class="text-sm text-gray-400 line-through">₹{{ number_format($product->price, 2) }}</p>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $productSavingPercentage }}% Off
                            </span>
                        @elseif(
                            $selectedVariantCombination &&
                                $selectedVariantCombination->price &&
                                $selectedVariantCombination->price < $regularPrice)
                            @php
                                $variantSaving = $regularPrice - $selectedVariantCombination->price;
                                $variantSavingPercentage = round(($variantSaving / $regularPrice) * 100);
                            @endphp
                            <p class="text-sm text-gray-400 line-through">₹{{ number_format($regularPrice, 2) }}</p>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $variantSavingPercentage }}% Off
                            </span>
                        @endif
                    </div>

                    <!-- Variant Selection -->
                    @if (!empty($availableVariants))
                        @foreach ($availableVariants as $type => $values)
                            <div class="mt-6">
                                <h3 class="font-medium text-[#171717] mb-3">{{ ucfirst($type) }}</h3>
                                <div class="flex gap-2 mt-2 flex-wrap variant-buttons">
                                    @if (is_array($values) || is_object($values))
                                        @foreach ($values as $value)
                                            <button
                                                wire:click="selectVariant('{{ $type }}', '{{ $value }}')"
                                                class="variant-button {{ isset($selectedVariants[$type]) && $selectedVariants[$type] === $value ? 'selected' : '' }}"
                                                wire:loading.attr="disabled" wire:target="selectVariant"
                                                :disabled="$wire.disabledVariants['{{ $type }}'][
                                                    '{{ $value }}'
                                                ] ?? false"
                                                :class="{
                                                    'opacity-50 cursor-not-allowed': $wire.disabledVariants[
                                                        '{{ $type }}']['{{ $value }}']
                                                }">
                                                @if ($type === 'Color' && !empty($colorImages[$value]))
                                                    <img src="{{ $colorImages[$value] }}" alt="{{ $value }}"
                                                        class="w-8 h-8 object-cover rounded" />
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </button>
                                        @endforeach
                                    @else
                                        <p class="text-red-500">Invalid variant values for {{ $type }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-600">No variants available.</p>
                    @endif

                    <!-- Customization Section -->
                    @if ($product->is_customizable)
                        <div class="mb-6 mt-6 p-5 bg-[#f5f0ff] rounded-lg border border-[#e9d5ff]">
                            <div class="flex items-center mb-3">
                                <svg class="w-6 h-6 text-[#8f4da7] mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z">
                                    </path>
                                </svg>
                                <h3 class="text-lg font-medium text-[#171717]">Customization Available!</h3>
                            </div>
                            <p class="text-[#6b21a8] mb-4">This product can be customized according to your
                                requirements.</p>
                            <a href="{{ $this->getCustomizationWhatsappUrl($product->name) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors font-medium">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                                Contact for Customization
                            </a>
                        </div>
                    @endif

                    <!-- Desktop Buttons -->
                    @if ($hasStock)
                        <div class="desktop-buttons py-8 flex gap-3">
                            <button wire:navigate wire:click="addToCart({{ $product->id }})"
                                @click="buttonLoading = true"
                                class="flex-1 px-6 py-3.5 bg-[#171717] text-white rounded-lg hover:bg-[#8f4da7] transition-colors font-medium"
                                wire:loading.attr="disabled" wire:target="addToCart">
                                <span x-show="!buttonLoading">Add to Cart</span>
                                <span x-show="buttonLoading" class="inline-flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                            <button wire:navigate wire:click="buyNow" @click="buttonLoading = true"
                                class="flex-1 px-6 py-3.5 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3c93] transition-colors font-medium"
                                wire:loading.attr="disabled" wire:target="buyNow">
                                <span x-show="!buttonLoading">Buy Now</span>
                                <span x-show="buttonLoading" class="inline-flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                        </div>
                    @else
                        <div class="desktop-buttons mb-5 flex gap-3">
                            <button disabled
                                class="flex-1 px-6 py-3.5 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium">
                                Add to Cart
                            </button>
                            <button disabled
                                class="flex-1 px-6 py-3.5 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium">
                                Buy Now
                            </button>
                        </div>
                        <div class="mb-5 text-center">
                            <p class="text-red-600 font-medium">This product is currently unavailable</p>
                        </div>
                    @endif

                    <!-- Product Meta -->
                    <div class="space-y-3 text-sm text-gray-700">
                        @if ($product->category)
                            <p class="flex gap-2">
                                <span class="font-medium text-[#171717]">Category:</span>
                                <span>{{ $product->category->title }}</span>
                            </p>
                        @endif

                        @if ($product->brand)
                            <p class="flex gap-2">
                                <span class="font-medium text-[#171717]">Brand:</span>
                                <span>{{ $product->brand->name }}</span>
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Mobile Fixed Buttons -->
            @if ($hasStock)
                <div class="fixed-bottom-buttons">
                    <button wire:navigate wire:click="addToCart({{ $product->id }})" @click="buttonLoading = true"
                        class="flex-1 px-4 py-3 bg-[#171717] text-white rounded-lg hover:bg-[#8f4da7] transition-colors font-medium"
                        wire:loading.attr="disabled" wire:target="addToCart">
                        <span x-show="!buttonLoading">Add to Cart</span>
                        <span x-show="buttonLoading" class="inline-flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                    <button wire:click="buyNow" @click="buttonLoading = true"
                        class="flex-1 px-4 py-3 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3c93] transition-colors font-medium"
                        wire:loading.attr="disabled" wire:target="buyNow">
                        <span x-show="!buttonLoading">Buy Now</span>
                        <span x-show="buttonLoading" class="inline-flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                </div>
            @else
                <div class="fixed-bottom-buttons">
                    <button disabled
                        class="flex-1 px-4 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium">
                        Add to Cart
                    </button>
                    <button disabled
                        class="flex-1 px-4 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium">
                        Buy Now
                    </button>
                </div>
            @endif

            <!-- Tabs Section -->
            <div class="mb-16">
                <!-- Tab Headers -->
                <div class="flex gap-1 border-b border-gray-200 mb-6">
                    <button class="tab-button font-medium transition-colors text-[#171717]"
                        :class="{ 'active text-[#8f4da7]': activeTab === 'description' }"
                        @click="activeTab = 'description'">
                        Description
                    </button>
                    <button class="tab-button font-medium transition-colors text-[#171717]"
                        :class="{ 'active text-[#8f4da7]': activeTab === 'reviews' }" @click="activeTab = 'reviews'">
                        Reviews
                    </button>
                    <button class="tab-button font-medium transition-colors text-[#171717]"
                        :class="{ 'active text-[#8f4da7]': activeTab === 'additional' }"
                        @click="activeTab = 'additional'">
                        Info
                    </button>
                </div>

                <!-- Tab Contents -->
                <div x-show="activeTab === 'description'" class="tab-content">
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <div x-show="activeTab === 'reviews'"
                    class="tab-content p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-[400px]">
                    <div class="max-w-4xl mx-auto">
                        <!-- Header Section -->
                        <div class="mb-8 text-center">
                            <h2 class="text-3xl font-semibold text-gray-900 mb-2">Customer Reviews</h2>
                            <p class="text-gray-600">What our customers are saying about us</p>

                            <!-- Review Summary -->
                            <div class="mt-6 flex flex-wrap justify-center gap-4">
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                                    <div class="text-2xl font-semibold text-[#8f4da7]">{{ $approvedReviews->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600">Total Reviews</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                                    <div class="text-2xl font-semibold text-[#8f4da7]">
                                        {{ number_format($approvedReviews->avg('rating') ?? 0, 1) }}
                                    </div>
                                    <div class="text-sm text-gray-600">Average Rating</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                                    <div class="text-2xl font-semibold text-[#8f4da7]">5★</div>
                                    <div class="text-sm text-gray-600">Top Rating</div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @foreach ($approvedReviews as $review)
                                <div
                                    class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 overflow-hidden">
                                    <!-- Review Header -->
                                    <div class="p-6 pb-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center space-x-3">
                                                <!-- User Avatar -->
                                                <div
                                                    class="w-12 h-12 rounded-full bg-gradient-to-r from-[#8f4da7] to-purple-600 flex items-center justify-center text-white font-semibold text-lg">
                                                    {{ substr($review->user->name ?? 'A', 0, 1) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">
                                                        {{ $review->user->name ?? 'Anonymous' }}
                                                    </h4>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $review->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Rating Badge -->
                                            <div
                                                class="flex items-center space-x-1 bg-gray-100 px-3 py-1 rounded-full">
                                                <span
                                                    class="text-yellow-500 font-semibold">{{ $review->rating }}</span>
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Star Rating -->
                                    <div class="px-6 pb-3">
                                        <div class="flex space-x-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Review Content -->
                                    <div class="px-6 pb-6">
                                        <p class="text-gray-700 leading-relaxed line-clamp-4">
                                            {{ $review->comment }}
                                        </p>

                                        <!-- Review Actions -->
                                        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ $review->created_at->format('M d, Y') }}</span>
                                            </span>
                                            <div class="flex space-x-4">
                                                <button
                                                    class="flex items-center space-x-1 hover:text-[#8f4da7] transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                    </svg>
                                                    <span>Helpful</span>
                                                </button>
                                                <button
                                                    class="flex items-center space-x-1 hover:text-[#8f4da7] transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    <span>Reply</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Empty State -->
                        @if ($approvedReviews->isEmpty())
                            <div class="text-center py-16 bg-white rounded-2xl shadow-lg border border-gray-200">
                                <div class="max-w-md mx-auto">
                                    <div
                                        class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reviews Yet</h3>
                                    <p class="text-gray-600">Be the first to share your experience with others!</p>
                                </div>
                            </div>
                        @endif

                        <!-- Load More Button (if needed) -->
                        @if ($approvedReviews->count() > 8)
                            <div class="mt-8 text-center">
                                <button
                                    class="px-6 py-3 bg-gradient-to-r from-[#8f4da7] to-purple-600 text-white font-medium rounded-lg hover:from-[#7e3d96] hover:to-purple-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:ring-offset-2">
                                    Load More Reviews
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <style>
                    .line-clamp-4 {
                        display: -webkit-box;
                        -webkit-line-clamp: 4;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                    }

                    .tab-content {
                        animation: fadeIn 0.5s ease-in-out;
                    }

                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                            transform: translateY(10px);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                </style>

                <div x-show="activeTab === 'additional'" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-lg mb-3 text-[#171717]">Dimensions</h4>
                            <p class="text-gray-700">{{ $product->height }} x {{ $product->breadth }} x
                                {{ $product->length }} cm</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-lg mb-3 text-[#171717]">Weight</h4>
                            <p class="text-gray-700">{{ $product->weight }} kg</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-lg mb-3 text-[#171717]">Materials</h4>
                            <p class="text-gray-700">Premium quality, Eco-friendly</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-lg mb-3 text-[#171717]">Care Instructions</h4>
                            <p class="text-gray-700">Hand wash only. Do not bleach.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->isNotEmpty())
                <div class="mb-16">
                    <h2 class="text-2xl font-medium text-center mb-8 text-[#171717]">Related Products</h2>

                   <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3">
    @foreach ($relatedProducts as $relatedProduct)
        <div class="bg-white border border-[#dedada] rounded-lg overflow-hidden w-full mx-auto transition-all duration-300">
            <!-- Image Section -->
            <div class="relative p-1 sm:p-2">
                <a href="{{ route('view.product', $relatedProduct->slug) }}">
                    @if ($relatedProduct->firstVariantImage)
                        <div class="aspect-[3/4] bg-gray-50 flex items-center justify-center overflow-hidden rounded-md">
                            <img src="{{ $relatedProduct->firstVariantImage->image_path ?? asset('images/placeholder.jpg') }}"
                                 alt="{{ $relatedProduct->firstVariantImage->name }}"
                                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                        </div>
                    @else
                        <div class="aspect-[3/4] bg-gray-50 flex items-center justify-center rounded-md">
                            <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder"
                                 class="w-full h-full object-cover" />
                        </div>
                    @endif
                </a>
                <!-- Wishlist Button -->
                <div class="absolute top-2 right-2 sm:top-3 sm:right-3">
                    <livewire:public.section.wishlist-button :productId="$relatedProduct->id" />
                </div>
            </div>
            <!-- Content Section -->
            <div class="p-1 sm:p-2">
                <a href="{{ route('view.product', $relatedProduct->slug) }}">
                    <!-- Price Section -->
                    <div class="flex items-center justify-center gap-1 mb-1 sm:mb-2 flex-wrap">
                        <span class="text-xs sm:text-sm font-semibold text-[#8f4da7]">
                            ₹{{ $relatedProduct->discount_price ?? $relatedProduct->price }}
                        </span>
                        @if ($relatedProduct->discount_price && $relatedProduct->discount_price < $relatedProduct->price)
                            <span class="text-[10px] sm:text-xs text-[#8A8E92] line-through">
                                ₹{{ $relatedProduct->price }}
                            </span>
                            @php
                                $discount = 0;
                                if ($relatedProduct->price > 0 && $relatedProduct->discount_price < $relatedProduct->price) {
                                    $discount = round(
                                        (($relatedProduct->price - $relatedProduct->discount_price) / $relatedProduct->price) * 100,
                                    );
                                }
                            @endphp
                            @if ($discount > 0)
                                <span class="text-[10px] font-semibold text-green-600 bg-green-50 px-1 py-0.5 rounded-full">
                                    {{ $discount }}% OFF
                                </span>
                            @endif
                        @endif
                    </div>
                    <!-- Product Name -->
                    <h3 class="text-[#3e3f40] text-[10px] sm:text-xs font-medium text-center line-clamp-2 min-h-[1.5rem] sm:min-h-[1.5rem] leading-tight truncate">
                        {{ $relatedProduct->name }}
                    </h3>
                    <!-- CTA Button -->
                    <div class="border border-[#8f4da7] text-[#8f4da7] hover:bg-[#8f4da7] hover:text-white py-2 px-2 rounded-full text-[10px] sm:text-xs font-medium transition-all duration-300 text-center">
                        <i class="fas fa-arrow-right mr-1"></i>View Product
                    </div>
                </a>
            </div>
        </div>
    @endforeach
</div>
                </div>
            @endif


            <!-- Fullscreen Modal -->
            <div x-show="isFullscreen"
                class="fixed inset-0 bg-black bg-opacity-95 z-50 flex items-center justify-center"
                @click.self="isFullscreen = false">
                <div class="relative max-w-4xl w-full p-4">
                    <button
                        class="absolute top-4 right-4 text-white text-2xl z-10 bg-[#8f4da7] rounded-full w-10 h-10 flex items-center justify-center"
                        @click="isFullscreen = false">
                        <i class="fas fa-times"></i>
                    </button>
                    <img x-bind:src="images[activeImageIndex]" class="w-full max-h-screen object-contain"
                        alt="Fullscreen product image">
                </div>
            </div>

            <!-- Back to Top Button -->
            <button class="back-to-top" :class="{ 'visible': showBackToTop }" @click="scrollToTop()">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>

        @livewireScripts
        <script>
            function productPage() {
                return {
                    activeImageIndex: 0,
                    activeTab: 'description',
                    isFullscreen: false,
                    rating: 0,
                    showBackToTop: false,
                    images: [],
                    thumbs: [],
                    notification: {
                        message: '',
                        type: ''
                    },

                    init() {
                        this.updateImages();

                        // Listen for custom events
                        window.addEventListener('variantUpdated', (event) => {
                            console.log('Variant updated event:', event);
                            if (event.detail && event.detail.image) {
                                // Get the main image and gallery
                                let mainImage = event.detail.image;
                                let gallery = event.detail.galleryImages || [];

                                // Remove duplicates
                                gallery = gallery.filter(img => img !== mainImage);

                                // Update the images array
                                this.images = [mainImage, ...gallery];
                                this.thumbs = [mainImage, ...gallery];

                                // Force reset active image to show the new main image
                                this.activeImageIndex = 0;

                                console.log('Images updated:', {
                                    mainImage,
                                    gallery,
                                    allImages: this.images,
                                    activeIndex: this.activeImageIndex
                                });
                            }
                        });

                        window.addEventListener('scroll', () => {
                            this.showBackToTop = window.scrollY > 300;
                        });

                        Livewire.on('notify', (event) => {
                            this.notification = event;
                            setTimeout(() => {
                                this.notification.message = '';
                            }, 3000);
                        });

                        // Listen for Livewire updates to force refresh
                        Livewire.on('$refresh', () => {
                            console.log('Livewire refreshing, updating images');
                            this.updateImages();
                        });

                        // Initial debug log
                        console.log('Alpine.js initialized');
                    },

                    updateImages() {
                        console.log('updateImages called');
                        const variantImage = @json($variantImage);
                        const productImages = @json($formattedProductImages);
                        const placeholder = '{{ asset('images/placeholder.jpg') }}';

                        if (variantImage) {
                            this.images = [variantImage, ...productImages];
                            this.thumbs = [variantImage, ...productImages];
                            console.log('Using variant image as primary');
                        } else if (productImages && productImages.length > 0) {
                            this.images = productImages;
                            this.thumbs = productImages;
                            console.log('Using product images only');
                        } else {
                            this.images = [placeholder];
                            this.thumbs = [placeholder];
                            console.log('Using placeholder image');
                        }
                        console.log('Updated images:', this.images);
                    },

                    setActiveImage(index) {
                        this.activeImageIndex = index;
                        console.log('Set active image index:', index);
                    },

                    openFullscreen() {
                        this.isFullscreen = true;
                        console.log('Opening fullscreen');
                    },

                    zoomImage(event) {
                        // Placeholder for zooming functionality
                    },

                    setRating(stars) {
                        this.rating = stars;
                        const starIcons = event.currentTarget.parentElement.querySelectorAll('i');
                        starIcons.forEach((icon, index) => {
                            if (index < stars) {
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                            } else {
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                            }
                        });
                        console.log('Rating set to:', stars);
                    },

                    scrollToTop() {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                        console.log('Scrolling to top');
                    },

                    async shareProduct() {
                        const productName = @json($product->name);
                        const productUrl = window.location.href;
                        const productImage = this.images[this.activeImageIndex] || @json(asset('images/placeholder.jpg'));

                        try {
                            if (navigator.share) {
                                // Use Web Share API if available
                                await navigator.share({
                                    title: productName,
                                    text: `Check out this product: ${productName}`,
                                    url: productUrl,
                                });
                                console.log('Product shared successfully');
                                this.notification = {
                                    message: 'Product shared successfully!',
                                    type: 'success'
                                };
                            } else {
                                // Fallback to copying link to clipboard
                                await navigator.clipboard.writeText(productUrl);
                                console.log('Link copied to clipboard');
                                this.notification = {
                                    message: 'Link copied to clipboard!',
                                    type: 'success'
                                };
                            }
                        } catch (error) {
                            console.error('Error sharing product:', error);
                            this.notification = {
                                message: 'Failed to share product. Please try again.',
                                type: 'error'
                            };
                        }
                        setTimeout(() => {
                            this.notification.message = '';
                        }, 3000);
                    }
                };
            }

            // Additional debug script to monitor Livewire updates
            document.addEventListener('livewire:init', () => {
                console.log('Livewire initialized');
            });

            document.addEventListener('livewire:update', () => {
                console.log('Livewire updating DOM');
            });

            document.addEventListener('livewire:updated', () => {
                console.log('Livewire DOM updated');
            });
        </script>
    </div>
</div>
