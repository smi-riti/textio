<div class="bg-gray-50 text-gray-800">

{{-- <x-public-header/> --}}

    <!-- Hero Slider -->
    <div class="bg-gray-800">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide relative">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-700 h-96 md:h-[500px]">
                    <div class="container mx-auto px-4 h-full flex items-center">
                        <div class="w-full md:w-1/2 text-white">
                            <h1 class="text-4xl md:text-5xl font-bold mb-4">Summer Collection 2025</h1>
                            <p class="text-lg mb-6">Discover the latest trends and styles for the summer season. Up to 40% off on selected items.</p>
                            <div class="flex space-x-4">
                                <a href="#" class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg hover:bg-gray-100 transition">Shop Now</a>
                                <a href="#" class="px-6 py-3 border border-white text-white font-medium rounded-lg hover:bg-white hover:text-indigo-600 transition">View Collection</a>
                            </div>
                        </div>
                        <div class="hidden md:block w-1/2">
                            <img src="https://via.placeholder.com/600x400" alt="Summer Collection" class="ml-auto animate-float">
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

    <!-- Flash Sale -->
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <h2 class="text-2xl md:text-3xl font-bold">Flash Sale</h2>
                    <div class="ml-4 sale-timer px-4 py-2 rounded-lg text-white flex items-center">
                        <i class="far fa-clock mr-2"></i>
                        <span>Ends in: </span>
                        <span class="ml-2 font-mono">23:59:59</span>
                    </div>
                </div>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product 1 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                    <div class="relative">
                        <span class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 product-badge">-40%</span>
                        <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-1">Electronics</div>
                        <h3 class="font-medium mb-1">Wireless Bluetooth Headphones</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-sm text-gray-500 ml-2">(45)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-indigo-600">$59.99</span>
                                <span class="text-sm text-gray-400 line-through ml-1">$99.99</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                    <div class="relative">
                        <span class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 product-badge">-25%</span>
                        <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-1">Fashion</div>
                        <h3 class="font-medium mb-1">Men's Casual Jacket</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-500 ml-2">(32)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-indigo-600">$74.99</span>
                                <span class="text-sm text-gray-400 line-through ml-1">$99.99</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                    <div class="relative">
                        <span class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 product-badge">-50%</span>
                        <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-1">Home</div>
                        <h3 class="font-medium mb-1">Smart LED Table Lamp</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-500 ml-2">(78)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-indigo-600">$29.99</span>
                                <span class="text-sm text-gray-400 line-through ml-1">$59.99</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product 4 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                    <div class="relative">
                        <span class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 product-badge">-30%</span>
                        <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-1">Beauty</div>
                        <h3 class="font-medium mb-1">Luxury Skincare Set</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-500 ml-2">(56)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-indigo-600">$69.99</span>
                                <span class="text-sm text-gray-400 line-through ml-1">$99.99</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Banner -->
    <section class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="relative rounded-lg overflow-hidden h-64 shadow-lg">
                <img src="https://via.placeholder.com/800x400" alt="Banner" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/80 to-transparent flex items-center">
                    <div class="p-8 text-white">
                        <h3 class="text-2xl font-bold mb-2">New Season Arrivals</h3>
                        <p class="mb-4">Check out the latest styles and trends</p>
                        <a href="#" class="bg-white text-blue-600 px-4 py-2 rounded-full font-medium hover:bg-blue-50 transition">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="relative rounded-lg overflow-hidden h-64 shadow-lg">
                <img src="https://via.placeholder.com/800x400" alt="Banner" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600/80 to-transparent flex items-center">
                    <div class="p-8 text-white">
                        <h3 class="text-2xl font-bold mb-2">Electronics Sale</h3>
                        <p class="mb-4">Get up to 40% off on selected items</p>
                        <a href="#" class="bg-white text-purple-600 px-4 py-2 rounded-full font-medium hover:bg-purple-50 transition">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Products -->
    <section class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold">Popular Products</h2>
            <div class="space-x-2">
                <button class="px-3 py-1 bg-indigo-600 text-white rounded-md">All</button>
                <button class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">New</button>
                <button class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">Featured</button>
                <button class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">Sale</button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <!-- Product 1 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <div class="relative">
                    <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-sm text-gray-500 mb-1">Electronics</div>
                    <h3 class="font-medium mb-1">Smartphone X Pro</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-sm text-gray-500 ml-2">(125)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">$899.99</span>
                        <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <div class="relative">
                    <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-sm text-gray-500 mb-1">Clothing</div>
                    <h3 class="font-medium mb-1">Formal Slim Fit Shirt</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="text-sm text-gray-500 ml-2">(84)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">$49.99</span>
                        <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <div class="relative">
                    <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-sm text-gray-500 mb-1">Home</div>
                    <h3 class="font-medium mb-1">Modern Coffee Table</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-sm text-gray-500 ml-2">(62)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">$199.99</span>
                        <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <div class="relative">
                    <span class="absolute top-0 left-0 bg-green-500 text-white px-3 py-1 product-badge">New</span>
                    <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-sm text-gray-500 mb-1">Sports</div>
                    <h3 class="font-medium mb-1">Running Shoes</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="text-sm text-gray-500 ml-2">(38)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">$129.99</span>
                        <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Product 5 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <div class="relative">
                    <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="bg-white p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-sm text-gray-500 mb-1">Jewelry</div>
                    <h3 class="font-medium mb-1">Silver Necklace</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-sm text-gray-500 ml-2">(94)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">$79.99</span>
                        <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

   

    <!-- Brands -->
  @livewire('public.brand-display')


     <!-- Popular Products -->
  @livewire('public.recent-product')

    <!-- Latest Blog -->
    <section class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold">Latest Articles</h2>
            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">View All Posts</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Blog Post 1 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <img src="https://via.placeholder.com/600x400" alt="Blog" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="far fa-calendar-alt mr-2"></i> May 10, 2025
                        <span class="mx-2">•</span>
                        <i class="far fa-comment mr-2"></i> 24 Comments
                    </div>
                    <h3 class="font-bold text-xl mb-2">Summer Fashion Trends for 2025</h3>
                    <p class="text-gray-600 mb-4">Discover the hottest fashion trends for the upcoming summer season and stay ahead of the curve.</p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Post 2 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <img src="https://via.placeholder.com/600x400" alt="Blog" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="far fa-calendar-alt mr-2"></i> May 5, 2025
                        <span class="mx-2">•</span>
                        <i class="far fa-comment mr-2"></i> 18 Comments
                    </div>
                    <h3 class="font-bold text-xl mb-2">Tech Gadgets You Need in 2025</h3>
                    <p class="text-gray-600 mb-4">Explore the latest tech gadgets that are revolutionizing how we live, work, and play in 2025.</p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Post 3 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <img src="https://via.placeholder.com/600x400" alt="Blog" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="far fa-calendar-alt mr-2"></i> April 28, 2025
                        <span class="mx-2">•</span>
                        <i class="far fa-comment mr-2"></i> 32 Comments
                    </div>
                    <h3 class="font-bold text-xl mb-2">Home Decor Ideas for Small Spaces</h3>
                    <p class="text-gray-600 mb-4">Transform your small living space with these creative and practical home decor ideas.</p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Read More →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="bg-indigo-600 py-12 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Subscribe to Our Newsletter</h2>
            <p class="mb-6 max-w-xl mx-auto">Stay up to date with the latest products, exclusive offers, and news directly to your inbox.</p>
            <div class="flex flex-col md:flex-row max-w-xl mx-auto">
                <input type="email" placeholder="Enter your email" class="w-full md:flex-1 px-4 py-3 rounded-l-lg text-gray-900 focus:outline-none">
                <button class="mt-2 md:mt-0 px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg md:rounded-l-none md:rounded-r-lg hover:bg-indigo-100 transition">Subscribe</button>
            </div>
        </div>
    </section>

  

    <!-- Scripts -->
    {{-- <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
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
        document.querySelector('.fixed.bottom-6.right-6').addEventListener('click', function() {
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
        
        .swiper-button-next, .swiper-button-prev {
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-position: center;
            background-size: 20px;
        }
        
        .swiper-button-next:after, .swiper-button-prev:after {
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
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</div>