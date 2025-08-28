<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4">Featured Products</h2>
        <p class="text-center text-gray-600 mb-12">Discover our most popular custom printing products</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Product Card 1 -->

            <a href="{{route('view.product')}}"
                class="product-card  rounded-xl overflow-hidden  hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 max-w-sm mx-auto">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1480&q=80"
                        alt="Custom T-Shirt" class="w-full h-64 object-cover">
                    <div
                        class="absolute top-3 right-3 bg-red-500 text-white text-xs font-semibold px-2.5 py-1.5 rounded-full uppercase tracking-wide">
                        Sale
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Custom Premium T-Shirt</h3>
                    <p class="text-gray-500 text-sm mb-4">100% Cotton, Premium Fit</p>
                    <div class="flex justify-center items-center gap-3 mb-6">
                        <span class="text-lg font-bold text-purple-600">₹24.99</span>
                        <span class="text-sm text-gray-400 line-through">₹29.99</span>
                    </div>
                    <button
                        class="add-to-cart-btn w-full bg-gray-800  text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
                        @click="cartItems++">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                </div>
            </a>


             <div
                class="product-card  rounded-xl overflow-hidden  hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 max-w-sm mx-auto">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1480&q=80"
                        alt="Custom T-Shirt" class="w-full h-64 object-cover">
                    <div
                        class="absolute top-3 right-3 bg-red-500 text-white text-xs font-semibold px-2.5 py-1.5 rounded-full uppercase tracking-wide">
                        Sale
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Custom Premium T-Shirt</h3>
                    <p class="text-gray-500 text-sm mb-4">100% Cotton, Premium Fit</p>
                    <div class="flex justify-center items-center gap-3 mb-6">
                        <span class="text-lg font-bold text-purple-600">₹24.99</span>
                        <span class="text-sm text-gray-400 line-through">₹29.99</span>
                    </div>
                    <button
                        class="add-to-cart-btn w-full bg-gray-800  text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
                        @click="cartItems++">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                </div>
            </div>


             <div
                class="product-card  rounded-xl overflow-hidden  hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 max-w-sm mx-auto">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1480&q=80"
                        alt="Custom T-Shirt" class="w-full h-64 object-cover">
                    <div
                        class="absolute top-3 right-3 bg-red-500 text-white text-xs font-semibold px-2.5 py-1.5 rounded-full uppercase tracking-wide">
                        Sale
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Custom Premium T-Shirt</h3>
                    <p class="text-gray-500 text-sm mb-4">100% Cotton, Premium Fit</p>
                    <div class="flex justify-center items-center gap-3 mb-6">
                        <span class="text-lg font-bold text-purple-600">₹24.99</span>
                        <span class="text-sm text-gray-400 line-through">₹29.99</span>
                    </div>
                    <button
                        class="add-to-cart-btn w-full bg-gray-800  text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
                        @click="cartItems++">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                </div>
            </div>


             <div
                class="product-card  rounded-xl overflow-hidden  hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 max-w-sm mx-auto">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1480&q=80"
                        alt="Custom T-Shirt" class="w-full h-64 object-cover">
                    <div
                        class="absolute top-3 right-3 bg-red-500 text-white text-xs font-semibold px-2.5 py-1.5 rounded-full uppercase tracking-wide">
                        Sale
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Custom Premium T-Shirt</h3>
                    <p class="text-gray-500 text-sm mb-4">100% Cotton, Premium Fit</p>
                    <div class="flex justify-center items-center gap-3 mb-6">
                        <span class="text-lg font-bold text-purple-600">₹24.99</span>
                        <span class="text-sm text-gray-400 line-through">₹29.99</span>
                    </div>
                    <button
                        class="add-to-cart-btn w-full bg-gray-800  text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
                        @click="cartItems++">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                </div>
            </div>
            <!-- Product Card 2 -->
            {{-- <div class="product-card bg-white rounded-xl  overflow-hidden shadow-md">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1544&q=80"
                        alt="Custom Hoodie" class="w-full h-56 object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1">Personalized Hoodie</h3>
                    <p class="text-gray-600 text-sm mb-3">Warm and comfortable</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-purple-600">₹39.99</span>
                        </div>
                        <button
                            class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                            @click="cartItems++">
                            <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1544787219-7f47ccb76574?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1468&q=80"
                        alt="Custom Mug" class="w-full h-56 object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1">Custom Printed Mug</h3>
                    <p class="text-gray-600 text-sm mb-3">Dishwasher safe, 11oz</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-purple-600">₹14.99</span>
                        </div>
                        <button
                            class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                            @click="cartItems++">
                            <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 4 -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1534215754734-18e55d13e346?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1408&q=80"
                        alt="Custom Cap" class="w-full h-56 object-cover">
                    <div class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                        NEW
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1">Personalized Baseball Cap</h3>
                    <p class="text-gray-600 text-sm mb-3">Adjustable, one size fits all</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-purple-600">₹19.99</span>
                        </div>
                        <button
                            class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                            @click="cartItems++">
                            <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="text-center mt-12">
            <a href=""
                class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300">
                View All Products
            </a>
        </div>
    </div>
</section>
