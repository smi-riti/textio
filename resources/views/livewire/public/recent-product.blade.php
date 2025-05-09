<section class="container mx-auto px-4 py-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl md:text-3xl font-bold">Recent Products</h2>
        <div class="space-x-2">
            <button class="px-3 py-1 bg-indigo-600 text-white rounded-md">All</button>
            <button class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">New</button>
            <button class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">Featured</button>
            <button class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">Sale</button>
        </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        @foreach ($products as $product)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
            <div class="relative">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
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
                <div class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</div>
                <h3 class="font-medium mb-1">{{ $product->name }}</h3>
                <div class="flex items-center mb-2">
                    <div class="flex text-yellow-400">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fas fa-star{{ $i < $product->rating ? '' : '-half-alt' }}"></i>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500 ml-2">({{ $product->reviews_count }})</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                    @if ($product->discount_price)
                        <span class="text-sm text-gray-400 line-through ml-1">${{ number_format($product->price, 2) }}</span>
                    @endif
                    <button class="bg-indigo-600 text-white px-3 py-1 rounded-full hover:bg-indigo-700 transition">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
