<div wire:ignore.self x-data="productPage()">
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
                            <div class="relative overflow-hidden rounded-xl bg-gray-100">
                                <img x-bind:src="images[activeImageIndex]" class="w-full h-96 object-contain zoom-image"
                                    alt="{{ $product->name }}" @mousemove="zoomImage($event)">
                                <!-- Eye icon for fullscreen -->
                                <button
                                    class="absolute top-3 right-3 p-2 rounded-full bg-white shadow-md transition-colors hover:bg-gray-100"
                                    @click="openFullscreen()">
                                    <i class="fas fa-eye text-[#8f4da7]"></i>
                                </button>
                                <!-- Wishlist button component -->
                                <div class="absolute top-3 right-14">
                                    <livewire:public.section.wishlist-button :productId="$product->id" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="lg:w-1/2">
                    <h1 class="text-2xl font-sans font-semibold text-[#171717] mb-3">{{ $product->name }}</h1>

                    <!-- Ratings -->
                    <div class="flex items-center gap-2 mb-5">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="text-sm text-gray-600">(4 reviews)</span>
                    </div>

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
                    @if($hasStock)
                    <div class="desktop-buttons mb-5">
                        <button wire:navigate wire:click="addToCart({{ $product->id }})"
                            class="flex-1 px-6 py-3.5 bg-[#171717] text-white rounded-lg hover:bg-[#8f4da7] transition-colors font-medium"
                            wire:loading.attr="disabled" wire:target="addToCart">
                            Add to Cart
                        </button>
                        <button wire:navigate wire:click="buyNow"
                            class="flex-1 px-6 py-3.5 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3c93] transition-colors font-medium"
                            wire:loading.attr="disabled" wire:target="buyNow">
                            Buy Now
                        </button>
                    </div>
                    @else
                        <div class="desktop-buttons mb-5">
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
                        <p class="flex gap-2">
                            <span class="font-medium text-[#171717]">SKU:</span>
                            <span>{{ $sku }}</span>
                        </p>
                        <p class="flex gap-2">
                            <span class="font-medium text-[#171717]">Category:</span>
                            <span>{{ $product->category ? $product->category->title : 'No Category' }}</span>
                        </p>
                        <p class="flex gap-2">
                            <span class="font-medium text-[#171717]">Tags:</span>
                            <span>Cup, Magazine, Poster, T-shirt</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Mobile Fixed Buttons -->
            @if($hasStock)
            <div class="fixed-bottom-buttons">
                <button wire:click="addToCart({{ $product->id }})"
                    class="flex-1 px-4 py-3 bg-[#171717] text-white rounded-lg hover:bg-[#8f4da7] transition-colors font-medium"
                    wire:loading.attr="disabled" wire:target="addToCart">
                    Add to Cart
                </button>
                <button wire:click="buyNow"
                    class="flex-1 px-4 py-3 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3c93] transition-colors font-medium"
                    wire:loading.attr="disabled" wire:target="buyNow">
                    Buy Now
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
                        Additional Info
                    </button>
                </div>

                <!-- Tab Contents -->
                <div x-show="activeTab === 'description'" class="tab-content">
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <div x-show="activeTab === 'reviews'" class="tab-content">
                    <div class="space-y-6">
                        <!-- Review Form -->
                        <div class="pt-4">
                            <h3 class="text-xl font-medium text-[#171717] mb-4">Add a Review</h3>
                            <form class="space-y-4" wire:submit.prevent="submitReview">
                                <div class="flex gap-1 text-2xl text-yellow-400 mb-4">
                                    <i class="far fa-star cursor-pointer hover:scale-110 transition-transform"
                                        @click="setRating(1)"></i>
                                    <i class="far fa-star cursor-pointer hover:scale-110 transition-transform"
                                        @click="setRating(2)"></i>
                                    <i class="far fa-star cursor-pointer hover:scale-110 transition-transform"
                                        @click="setRating(3)"></i>
                                    <i class="far fa-star cursor-pointer hover:scale-110 transition-transform"
                                        @click="setRating(4)"></i>
                                    <i class="far fa-star cursor-pointer hover:scale-110 transition-transform"
                                        @click="setRating(5)"></i>
                                </div>
                                <div>
                                    <textarea
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent"
                                        rows="4" placeholder="Your review" wire:model="review"></textarea>
                                    @error('review')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <input type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent"
                                            placeholder="Your Name" wire:model="name">
                                        @error('name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <input type="email"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent"
                                            placeholder="Your Email" wire:model="email">
                                        @error('email')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit"
                                    class="px-6 py-3 bg-[#171717] text-white rounded-lg hover:bg-[#8f4da7] transition-colors font-medium">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'additional'" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-lg mb-3 text-[#171717]">Dimensions</h4>
                            <p class="text-gray-700">10 x 20 x 5 cm</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-lg mb-3 text-[#171717]">Weight</h4>
                            <p class="text-gray-700">0.5 kg</p>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div
                                class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <a href="{{ route('view.product', $relatedProduct->slug) }}">
                                    <div class="relative">
                                        <img src="{{ $relatedProduct->images->first() ? asset($relatedProduct->images->first()->image_path) : asset('images/placeholder.jpg') }}"
                                            alt="{{ $relatedProduct->name }}" class="w-full h-64 object-cover">
                                        @if ($relatedProduct->discount_price && $relatedProduct->discount_price < $relatedProduct->price)
                                            <div
                                                class="absolute top-3 right-3 bg-red-500 text-white text-xs font-medium px-2.5 py-1.5 rounded-full">
                                                Sale
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                <div class="p-6 text-center">
                                    <h3 class="text-lg font-medium text-[#171717] truncate mb-2">
                                        {{ $relatedProduct->name }}</h3>
                                    <div class="flex justify-center items-center gap-3 mb-4">
                                        <span
                                            class="text-lg font-medium text-[#8f4da7]">{{ $relatedProduct->formatted_discount_price ?? $relatedProduct->formatted_price }}</span>
                                        @if ($relatedProduct->discount_price && $relatedProduct->discount_price < $relatedProduct->price)
                                            <span
                                                class="text-sm text-gray-400 line-through">{{ $relatedProduct->formatted_price }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('view.product', $relatedProduct->slug) }}">
                                        <button
                                            class="w-full bg-[#171717] text-white py-2.5 px-4 rounded-lg text-sm font-medium hover:bg-[#8f4da7] transition-colors">
                                            View Product
                                        </button>
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
                            this.updateImages();
                            this.activeImageIndex = 0;
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

                        // Use the data passed from Livewire through Blade templates
                        const variantImage = @json($variantImage);
                        console.log('Variant image from Blade:', variantImage);

                        // Get pre-formatted product images from Blade
                        const productImages = @json($formattedProductImages);
                        console.log('Product images from Blade:', productImages);

                        const placeholder = '{{ asset('images/placeholder.jpg') }}';

                        // If variant has its own image, use it as the primary image
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

                        // Always reset to the first image when variants change
                        this.activeImageIndex = 0;

                        console.log('Updated images:', this.images);
                        console.log('Active image index:', this.activeImageIndex);
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