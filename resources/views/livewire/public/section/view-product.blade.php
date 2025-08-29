<div>
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
            border-color: #8B5CF6;
        }

        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #8B5CF6;
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
            background-color: #7C3AED;
        }

        .fixed-bottom-buttons {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 12px 16px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }

        @media (max-width: 767px) {
            .fixed-bottom-buttons {
                display: flex;
                gap: 10px;
            }

            .back-to-top {
                display: none;
            }
        }

        @media (min-width: 768px) {
            .desktop-buttons {
                display: flex;
                gap: 10px;
            }

            .fixed-bottom-buttons {
                display: none;
            }
        }
    </style>

    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-2 py-5" x-data="productPage()">
            <!-- Back to Top Button -->
            <button class="back-to-top" :class="{ 'visible': showBackToTop }" @click="scrollToTop()" title="Back to Top">
                <i class="fas fa-chevron-up"></i>
            </button>

            <!-- Product Section -->
            <div class="flex flex-col lg:flex-row gap-8 mb-16">
                <!-- Product Images -->
                <div class="lg:w-1/2">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Thumbnails -->
                        <div class="flex md:flex-col gap-3 order-2 md:order-1">
                            @if ($product->images->isNotEmpty())
                                @foreach ($product->images as $index => $image)
                                    <img :src="'{{ $image->image_path }}'"
                                        class="w-16 h-16 object-cover product-thumb cursor-pointer rounded-lg"
                                        :class="{ 'active': activeImageIndex === {{ $index }} }"
                                        @click="setActiveImage({{ $index }})" alt="Thumbnail">
                                @endforeach
                            @else
                                <img :src="'{{ asset('images/placeholder.jpg') }}'"
                                    class="w-16 h-16 object-cover product-thumb cursor-pointer rounded-lg"
                                    :class="{ 'active': activeImageIndex === 0 }" @click="setActiveImage(0)"
                                    alt="Placeholder Thumbnail">
                            @endif
                        </div>

                        <!-- Main Image -->
                        <div class="relative order-1 md:order-2 flex-1">
                            <div class="relative overflow-hidden rounded-xl bg-gray-100">
                                <img :src="images[activeImageIndex]" class="w-full h-96 object-contain zoom-image"
                                    alt="{{ $product->name }}" @mousemove="zoomImage($event)">
                                <!-- Eye icon for fullscreen -->
                                <button
                                    class="absolute top-3 right-3 bg-white p-2 rounded-full shadow-md hover:bg-purple-500 hover:text-white transition-colors"
                                    @click="openFullscreen()">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="lg:w-1/2">
                    <h1 class="text-3xl font-semibold text-gray-900 mb-2">{{ $product->name }}</h1>

                    <!-- Ratings -->
                    <div class="flex items-center gap-2 mb-4">
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
                    <div class="flex items-center gap-3 mb-6 mt-5">
                        <p class="text-2xl font-semibold text-purple-600">
                            {{ $product->formatted_discount_price ?? $product->formatted_price }}</p>
                        @if ($product->discount_price && $product->discount_price < $product->price)
                            <p class="text-sm text-gray-400 line-through">{{ $product->formatted_price }}</p>
                            <p class="text-sm text-green-600">{{ $product->saving_percentage }}% Off</p>
                        @endif
                    </div>

                    <hr class="mb-6">

                    <!-- Desktop Buttons -->
                    <div class="desktop-buttons mb-4 hidden">
                        <button
                            class="flex-1 px-6 py-3 bg-gray-800 text-white rounded-full hover:bg-purple-600 transition-colors">
                            Add to Cart
                        </button>
                        <button
                            class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-full hover:bg-purple-800 transition-colors">
                            Buy Now
                        </button>
                    </div>

                    <a href="#" class="text-purple-600 hover:text-purple-800 flex items-center gap-2 mb-6">
                        <i class="far fa-heart"></i>
                        Browse wishlist
                    </a>

                    <!-- Product Meta -->
                    <div class="space-y-2 text-sm text-gray-700">
                        <p class="flex gap-2">
                            <span class="font-semibold">SKU:</span>
                            <span>{{ $product->sku }}</span>
                        </p>
                        <p class="flex gap-2">
                            <span class="font-semibold">Category:</span>
                            <span>{{ $product->category ? $product->category->title : 'No Category' }}</span>
                        </p>
                        <p class="flex gap-2">
                            <span class="font-semibold">Tags:</span>
                            <span>Cup, Magazine, Poster, T-shirt</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Mobile Fixed Buttons -->
            <div class="fixed-bottom-buttons">
                <button
                    class="flex-1 px-6 py-3 bg-gray-800 text-white rounded-full hover:bg-purple-600 transition-colors">
                    Add to Cart
                </button>
                <button
                    class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-full hover:bg-purple-800 transition-colors">
                    Buy Now
                </button>
            </div>

            <!-- Tabs Section -->
            <div class="mb-16">
                <!-- Tab Headers -->
                <div class="flex gap-5 border-b border-gray-200 mb-6">
                    <button class="px-2 py-3 font-medium border-b-2 transition-colors"
                        :class="activeTab === 'description' ? 'border-purple-600 text-purple-600' :
                            'border-transparent text-gray-500 hover:text-gray-700'"
                        @click="activeTab = 'description'">
                        Description
                    </button>
                    <button class="px-2 py-3 font-medium border-b-2 transition-colors"
                        :class="activeTab === 'reviews' ? 'border-purple-600 text-purple-600' :
                            'border-transparent text-gray-500 hover:text-gray-700'"
                        @click="activeTab = 'reviews'">
                        Reviews 
                    </button>
                    <button class="px-2 py-3 font-medium border-b-2 transition-colors"
                        :class="activeTab === 'additional' ? 'border-purple-600 text-purple-600' :
                            'border-transparent text-gray-500 hover:text-gray-700'"
                        @click="activeTab = 'additional'">
                        Additional Information
                    </button>
                </div>

                <!-- Tab Contents -->
                <div x-show="activeTab === 'description'" class="tab-content">
                    <p class="text-gray-700">{{ $product->description }}</p>
                </div>

                <div x-show="activeTab === 'reviews'" class="tab-content">
                    <div class="space-y-6">
                        <!-- Review Form -->
                        <div class="pt-6">
                            <h3 class="text-xl font-semibold mb-4">Add a review</h3>
                            <form class="space-y-4" wire:submit.prevent="submitReview">
                                <div class="flex gap-2 text-2xl text-yellow-400 mb-4">
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
                                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                        rows="4" placeholder="Your review" wire:model="review"></textarea>
                                    @error('review')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <input type="text"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                            placeholder="Your Name" wire:model="name">
                                        @error('name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <input type="email"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                            placeholder="Your Email" wire:model="email">
                                        @error('email')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit"
                                    class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-purple-600 transition-colors">Submit
                                    Review</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'additional'" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-lg mb-3">Dimensions</h4>
                            <p class="text-gray-700">10 x 20 x 5 cm</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-3">Weight</h4>
                            <p class="text-gray-700">0.5 kg</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-3">Materials</h4>
                            <p class="text-gray-700">Premium quality, Eco-friendly</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-3">Care Instructions</h4>
                            <p class="text-gray-700">Hand wash only. Do not bleach.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->isNotEmpty())
                <div class="mb-16">
                    <h2 class="text-2xl font-semibold text-center mb-8">Related Products</h2>

                    <div class="relative" x-data="{ currentSlide: 0 }">
                        <!-- Slider Container -->
                        <div class="overflow-hidden">
                            <div class="flex transition-transform duration-300 ease-in-out"
                                :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                                @foreach ($relatedProducts as $relatedProduct)
                                    <div class="w-full md:w-1/2 lg:w-1/4 flex-shrink-0 px-3">
                                        <div
                                            class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                            <div class="relative">
                                                <img src="{{ $relatedProduct->images->first() ? asset($relatedProduct->images->first()->image_path) : asset('images/placeholder.jpg') }}"
                                                    alt="{{ $relatedProduct->name }}"
                                                    class="w-full h-64 object-cover">
                                                @if ($relatedProduct->discount_price && $relatedProduct->discount_price < $relatedProduct->price)
                                                    <div
                                                        class="absolute top-3 right-3 bg-red-500 text-white text-xs font-semibold px-2.5 py-1.5 rounded-full uppercase tracking-wide">
                                                        Sale
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="p-6 text-center">
                                                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                                    {{ $relatedProduct->name }}</h3>
                                                <div class="flex justify-center items-center gap-3 mb-6">
                                                    <span
                                                        class="text-lg font-semibold text-purple-600">{{ $relatedProduct->formatted_discount_price ?? $relatedProduct->formatted_price }}</span>
                                                    @if ($relatedProduct->discount_price && $relatedProduct->discount_price < $relatedProduct->price)
                                                        <span
                                                            class="text-sm text-gray-400 line-through">{{ $relatedProduct->formatted_price }}</span>
                                                    @endif
                                                </div>
                                                <button
                                                    class="w-full bg-gray-800 text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-purple-600 transition-colors">
                                                    <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <button
                            class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md hover:bg-purple-500 hover:text-white transition-colors -translate-x-2"
                            :class="{ 'opacity-50 cursor-not-allowed': currentSlide === 0 }"
                            :disabled="currentSlide === 0"
                            @click="currentSlide = currentSlide > 0 ? currentSlide - 1 : 0">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button
                            class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md hover:bg-purple-500 hover:text-white transition-colors translate-x-2"
                            :class="{ 'opacity-50 cursor-not-allowed': currentSlide >= {{ count($relatedProducts) - 4 }}"
                            :disabled="currentSlide >= {{ count($relatedProducts) - 4 }}"
                            @click="currentSlide = currentSlide < {{ count($relatedProducts) - 4 }} ? currentSlide + 1 : currentSlide">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Fullscreen Modal -->
            <div x-show="isFullscreen"
                class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center"
                @click.self="isFullscreen = false">
                <div class="relative max-w-4xl w-full">
                    <button class="absolute top-4 right-4 text-white text-2xl z-10" @click="isFullscreen = false">
                        <i class="fas fa-times"></i>
                    </button>
                    <img :src="images[activeImageIndex]" class="w-full max-h-screen object-contain"
                        alt="Fullscreen product image">
                </div>
            </div>
        </div>

        @livewireScripts
        <script>
            function productPage() {
                console.log('Alpine.js productPage initialized');
                return {
                    activeImageIndex: 0,
                    activeTab: 'description',
                    isFullscreen: false,
                    rating: 0,
                    showBackToTop: false,
                    images: [
                        @if ($product->images->isNotEmpty())
                            @foreach ($product->images as $image)
                                '{{ asset($image->image_path) }}',
                            @endforeach
                        @else
                            '{{ asset('images/placeholder.jpg') }}',
                        @endif
                    ],
                    thumbs: [
                        @if ($product->images->isNotEmpty())
                            @foreach ($product->images as $image)
                                '{{ asset($image->image_path) }}',
                            @endforeach
                        @else
                            '{{ asset('images/placeholder.jpg') }}',
                        @endif
                    ],
                    setActiveImage(index) {
                        this.activeImageIndex = index;
                    },
                    openFullscreen() {
                        this.isFullscreen = true;
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
                    },
                    scrollToTop() {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    },
                    init() {
                        window.addEventListener('scroll', () => {
                            this.showBackToTop = window.scrollY > 300;
                        });
                    }
                }
            }
        </script>
    </div>

</div>
