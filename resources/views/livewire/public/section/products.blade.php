<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4">Featured Products</h2>
        <p class="text-center text-gray-600 mb-12">Discover our most popular custom printing products</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Product Card 1 -->

           @foreach ($Products as $product)
            <a wire:navigate href="{{route('view.product',$product->slug)}}"
                class="product-card  rounded-xl overflow-hidden  hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 max-w-sm ">
                <div class="relative">
                   <img src="{{$product->image_path}}"
                        alt="Custom T-Shirt" class="w-full h-64 object-cover">
                    <div
                        class="absolute top-3 right-3 bg-red-500 text-white text-xs font-semibold px-2.5 py-1.5 rounded-full uppercase tracking-wide">
                        Sale
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 truncate" >{{$product->name}}</h3>
                    <div class="flex justify-center items-center gap-3 mb-6">
                        <span class="text-lg font-bold text-purple-600">₹{{$product->price}}</span>
                        <span class="text-sm text-gray-400 line-through">₹{{$product->discount_price}}</span>
                    </div>
                    <button
                        class="add-to-cart-btn w-full bg-gray-800  text-white py-3 px-6 rounded-full text-sm font-medium hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
                        @click="cartItems++">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                </div>
            </a>
               
           @endforeach


          


            
           
        </div>

        <div class="text-center mt-12">catalog
            <a href="{{ route('public.product.all') }}"
                class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300">
                View All Products
            </a>
        </div>
    </div>
</section>
