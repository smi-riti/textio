<div class="container min-h-screen bg-white mx-auto px-4 py-6 max-w-7xl flex flex-col lg:flex-row gap-6">
    <!-- Sidebar -->
   <livewire:public.section.accounts.sidebar/>

    <!-- Wishlist Content -->
    <div class="w-full lg:w-9/12">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900">My Wishlist ({{ count($wishlistItems) }})</h1>
            <a href="{{route('myCart')}}" class="lg:hidden flex items-center bg-gray-800 text-white py-2 px-4 rounded-full text-sm font-semibold hover:bg-purple-600 transition duration-300">
                <i class="fas fa-shopping-cart mr-2"></i> View Cart
            </a>
        </div>
        <div class="space-y-6">
            @forelse ($wishlistItems as $item)
                <div class="flex flex-col sm:flex-row items-start sm:items-center bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition duration-300">
                    <div class="w-24 h-24 flex-shrink-0 mb-4 sm:mb-0">
                        {{-- <img src="{{ $item->product->images->first()?->image_path ?? asset('') }}"
                            alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-md"> --}}
                             <img src="/assets/images/product_38.jpg"
                            alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-md">
                    </div>
                    <div class="flex-1 sm:pl-6">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $item->product->name }}</h3>
                            <button wire:click="removeFromWishlist({{ $item->product->id }})"
                                class="text-red-600 hover:text-red-800 transition duration-200">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 mt-2">
                            <span class="text-lg font-semibold text-purple-600">₹{{ number_format($item->product->discount_price, 2) }}</span>
                            @if ($item->product->discount_price)
                                <span class="text-sm text-gray-500 line-through">₹{{ number_format($item->product->price, 2) }}</span>
                                <span class="text-sm text-green-600 font-medium">
                                    {{ round((($item->product->price - $item->product->discount_price) / $item->product->discount_price) * 100) }}% off
                                </span>
                            @endif
                        </div>
                        <button wire:click="addToCart({{ $item->product->id }})"
                            class="mt-4 bg-gray-800 text-white py-2 px-4 rounded-full text-sm font-semibold hover:bg-purple-600 transition duration-300 transform hover:scale-105">
                            <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
                <hr class="border-gray-200">
            @empty
                <div class="text-center text-gray-600 py-12 bg-gray-50 rounded-xl">
                    <p class="text-lg">Your wishlist is empty.</p>
                    <a href="{{ route('shop') }}" class="mt-4 inline-block bg-purple-600 text-white py-2 px-6 rounded-full text-sm font-semibold hover:bg-purple-700 transition duration-300">
                        Start Shopping
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>