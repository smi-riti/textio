<div>
    <div class="container min-h-screen bg-white mx-auto px-4 py-6 max-w-7xl flex flex-col lg:flex-row gap-6">
    <!-- Sidebar -->
    <livewire:public.section.accounts.sidebar/>
   
    <!-- Wishlist Content -->
    <div class="w-full lg:w-9/12">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-[#171717]">My Wishlist ({{ count($wishlistItems) }})</h1>
            <a href="{{route('myCart')}}" class="lg:hidden flex items-center bg-[#171717] text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-[#8f4da7] transition duration-300">
                <i class="fas fa-shopping-cart mr-2"></i> View Cart
            </a>
        </div>
        
        <div class="space-y-4">
            @forelse ($wishlistItems as $item)
                <div class="flex flex-col sm:flex-row items-start bg-white rounded-xl border border-gray-100 p-4 hover:shadow-md transition duration-300">
                   <a href="{{route('view.product',$item->product->slug)}}" class="sm:flex-shrink-0">
                     <div class="w-24 h-24 mb-4 sm:mb-0 sm:mr-4">
                        <img src="{{ $item->product->images->first()->image_path }}"
                            alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                    </div>
                   </a>
                   
                    <div class="flex-1 w-full">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-[#171717] line-clamp-2">{{ $item->product->name }}</h3>
                            <button wire:click="removeFromWishlist({{ $item->product->id }})"
                                class="text-red-500 hover:text-red-700 transition duration-200 p-2 rounded-full hover:bg-red-50">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <span class="text-lg font-semibold text-[#8f4da7]">₹{{ number_format($item->product->discount_price, 2) }}</span>
                            @if ($item->product->discount_price)
                                <span class="text-sm text-gray-500 line-through">₹{{ number_format($item->product->price, 2) }}</span>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-medium">
                                    {{ round((($item->product->price - $item->product->discount_price) / $item->product->price) * 100) }}% off
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <a href="{{route('view.product',$item->product->slug)}}">
                                <button class="bg-[#171717] text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-[#8f4da7] transition duration-300 flex items-center">
                                    <i class="fas fa-arrow-right mr-2 text-xs"></i> Check It Out
                                </button>
                            </a>
                          
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-600 py-12 bg-gray-50 rounded-xl">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-lg text-[#171717] mb-2">Your wishlist is empty</p>
                    <p class="text-gray-500 mb-6">Save your favorite items here for later</p>
                    <a href="{{ route('public.product.all') }}" class="inline-block bg-[#8f4da7] text-white py-2.5 px-6 rounded-lg text-sm font-medium hover:bg-[#7a3c93] transition duration-300">
                        Start Shopping
                    </a>
                </div>
            @endforelse
        </div>
        
      
        
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    @media (max-width: 640px) {
        .container {
            padding-bottom: 80px;
        }
    }
</style>
</div>