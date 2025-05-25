<div class="bg-gray-50 text-gray-800">

    {{-- <x-public-header /> --}}

    <!-- Hero Slider -->
    <div class="bg-gray-800">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide relative">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-700 h-96 md:h-[500px]">
                    <div class="container mx-auto px-4 h-full flex items-center">
                        <div class="w-full md:w-1/2 text-white">
                            <h1 class="text-4xl md:text-5xl font-bold mb-4">Summer Collection 2025</h1>
                            <p class="text-lg mb-6">Discover the latest trends and styles for the summer season. Up to
                                40% off on selected items.</p>
                            <div class="flex space-x-4">
                                <a href="#"
                                    class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg hover:bg-gray-100 transition">Shop
                                    Now</a>
                                <a href="#"
                                    class="px-6 py-3 border border-white text-white font-medium rounded-lg hover:bg-white hover:text-indigo-600 transition">View
                                    Collection</a>
                            </div>
                        </div>
                        <div class="hidden md:block w-1/2">
                            <img src="https://via.placeholder.com/600x400" alt="Summer Collection"
                                class="ml-auto animate-float">
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Features -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                <div class="bg-indigo-100 p-3 rounded-full">
                    <i class="fas fa-truck text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold">Free Shipping</h3>
                    <p class="text-sm text-gray-600">On orders over $50</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                <div class="bg-indigo-100 p-3 rounded-full">
                    <i class="fas fa-sync-alt text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold">Easy Returns</h3>
                    <p class="text-sm text-gray-600">30 days return policy</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                <div class="bg-indigo-100 p-3 rounded-full">
                    <i class="fas fa-shield-alt text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold">Secure Payment</h3>
                    <p class="text-sm text-gray-600">100% secure checkout</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                <div class="bg-indigo-100 p-3 rounded-full">
                    <i class="fas fa-headset text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold">24/7 Support</h3>
                    <p class="text-sm text-gray-600">Dedicated customer service</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-2xl md:text-3xl font-bold mb-6">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

            @livewire('public.category-grid')

        </div>
    </section>


    <!-- Popular Products -->
    <section class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold">Popular Products</h2>
            <div class="space-x-2">
                <button wire:click="updatePopularFilter('all')"
                    class="px-3 py-1 {{ $popularFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }} rounded-md">All</button>
                <button wire:click="updatePopularFilter('new')"
                    class="px-3 py-1 {{ $popularFilter === 'new' ? 'bg-indigo-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }} rounded-md">New</button>
                <button wire:click="updatePopularFilter('sale')"
                    class="px-3 py-1 {{ $popularFilter === 'sale' ? 'bg-indigo-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }} rounded-md">Sale</button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @forelse($popularProducts as $product)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                    <div class="relative">
                        @if($product->discount_price)
                            <span class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 product-badge">
                                -{{ number_format(($product->price - $product->discount_price) / $product->price * 100, 0) }}%
                            </span>
                        @elseif($product->created_at->diffInDays(now()) <= 7)
                            <span class="absolute top-0 left-0 bg-green-500 text-white px-3 py-1 product-badge">New</span>
                        @endif

                        <img src="{{ $product->images->first()->url ?? 'https://via.placeholder.com/300' }}"
                            alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-1">{{ $product->category->name ?? 'Uncategorized' }}</div>
                        <h3 class="font-medium mb-1">{{ $product->name }}</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= ($product->average_rating ?? 0) ? 'fas fa-star' : 'far fa-star' }}"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500 ml-2">({{ $product->reviews_count ?? 0 }})</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span
                                    class="text-lg font-bold text-indigo-600">${{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                                @if($product->discount_price)
                                    <span
                                        class="text-sm text-gray-400 line-through ml-1">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <a class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition"
                                href="{{ route('public.product.detail', $product->slug) }}">
                                Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-600">No popular products available.</p>
            @endforelse
        </div>
    </section>



    <!-- Brands -->
    {{-- @livewire('public.brand-display') --}}


    <!-- Popular Products -->
    @livewire('public.recent-product')


    <!-- Newsletter -->
    <section class="bg-indigo-600 py-12 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Subscribe to Our Newsletter</h2>
            <p class="mb-6 max-w-xl mx-auto">Stay up to date with the latest products, exclusive offers, and news
                directly to your inbox.</p>
            <div class="flex flex-col md:flex-row max-w-xl mx-auto">
                <input type="email" placeholder="Enter your email"
                    class="w-full md:flex-1 px-4 py-3 rounded-l-lg text-gray-900 focus:outline-none">
                <button
                    class="mt-2 md:mt-0 px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg md:rounded-l-none md:rounded-r-lg hover:bg-indigo-100 transition">Subscribe</button>
            </div>
        </div>
    </section>



    <!-- Scripts -->
    {{--
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        var swiper = new Swiper('.hero-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        // Update Sale Timer
        function updateTimer() {
            // Implementation details here
        }

        // Scroll to top
        document.querySelector('.fixed.bottom-6.right-6').addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script> --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-position: center;
            background-size: 20px;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 16px;
            font-weight: bold;
        }

        .product-badge {
            clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 10% 100%, 0% 85%);
        }

        .sale-timer {
            background: linear-gradient(90deg, #ff4e50, #f9d423);
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }
    </style>
</div>