<div class="container mx-auto px-4 py-8">
    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a> > 
        <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600">Products</a> > 
        <span class="text-gray-800">{{ $product->name }}</span>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Column - Product Details -->
        <div class="lg:w-2/3">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Product Images -->
                <div class="md:w-2/5">
                    <div class="bordergenzia
                        <img :src="images[activeImageIndex]" class="w-full h-96 object-contain zoom-image" alt="{{ $product->name }}">
                    </div>
                    <div class="flex gap-2">
                        @foreach($product->images as $image)
                            <div class="border rounded p-1 w-1/4 cursor-pointer">
                                <img src="{{ $image->image_path }}" alt="Thumbnail" class="h-16 w-full object-contain">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Product Info -->
                <div class="md:w-3/5">
                    <h1 class="text-2xl font-semibold text-gray-800">{{ $product->name }}</h1>
                    <!-- ... (rest of the product info, including quantity, color, storage, and buttons) ... -->
                    <div class="mt-6 flex gap-4">
                        <button wire:click="addToCart" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-lg font-semibold shadow-md">
                            <i class="fas fa-shopping-cart mr-2"></i>ADD TO CART
                        </button>
                        <button class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold shadow-md">
                            <i class="fas fa-bolt mr-2"></i>BUY NOW
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold border-b pb-2">Product Details</h2>
                <div class="mt-4">{!! $product->description !!}</div>
            </div>
        </div>

        <!-- Right Column - Price Details -->
        <div class="lg:w-1/3">
            @livewire('user.product.price-details', [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $product->price,
                'discount' => $product->discount,
                'deliveryCharge' => $product->delivery_charge
            ])
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-image { transition: transform 0.3s; }
    .product-image:hover { transform: scale(1.05); }
    .quantity-input { -moz-appearance: textfield; }
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('refreshComponent', () => {
            Livewire.refresh();
        });
    });
</script>
@endpush