<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Confirm Cart Item</h2>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Product Image -->
        <div class="md:w-1/3">
            <div class="border rounded-lg p-4 flex items-center justify-center h-64">
                @if($product->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                         alt="{{ $product->name }}"
                         class="product-image max-h-56">
                @else
                    <img src="https://via.placeholder.com/280x280?text=Product+Image"
                         alt="{{ $product->name }}"
                         class="product-image max-h-56">
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="md:w-2/3">
            <h1 class="text-2xl font-semibold text-gray-800">{{ $product->name }}</h1>
            <div class="mt-4">
                <div class="text-2xl font-bold">₹{{ number_format($price - $discount) }}</div>
                <div class="text-sm text-gray-600">
                    <span class="line-through">₹{{ number_format($price) }}</span>
                    <span class="text-green-600 ml-2">
                        {{ $discount > 0 ? round(($discount/$price)*100) : 0 }}% off
                    </span>
                </div>
            </div>

            <!-- Color Selection -->
            @if($product->variants->where('type', 'color')->isNotEmpty())
                <div class="mt-6">
                    <h3 class="font-semibold">Color</h3>
                    <div class="flex gap-2 mt-2">
                        @foreach($product->variants->where('type', 'color') as $variant)
                            <button
                                wire:click="selectColor('{{ $variant->value }}')"
                                class="border rounded p-2 {{ $selectedColor === $variant->value ? 'border-blue-600 border-2' : 'border-gray-300' }}"
                            >
                                {{ $variant->value }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Storage Selection -->
            @if($product->variants->where('type', 'storage')->isNotEmpty())
                <div class="mt-6">
                    <h3 class="font-semibold">Storage</h3>
                    <div class="flex gap-2 mt-2">
                        @foreach($product->variants->where('type', 'storage') as $variant)
                            <button
                                wire:click="selectStorage('{{ $variant->value }}')"
                                class="border rounded p-2 {{ $selectedStorage === $variant->value ? 'border-blue-600 border-2' : 'border-gray-300' }}"
                            >
                                {{ $variant->value }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quantity Selection -->
            <div class="mt-6">
                <h3 class="font-semibold">Quantity</h3>
                <div class="flex items-center mt-2">
                    <button wire:click="decrement" class="border rounded-l px-3 py-1 bg-gray-100">-</button>
                    <input type="number"
                           wire:model="quantity"
                           class="w-12 text-center border-t border-b py-1 quantity-input">
                    <button wire:click="increment" class="border rounded-r px-3 py-1 bg-gray-100">+</button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-4">
                <button
                    wire:click="addToCart"
                    class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-lg font-semibold shadow-md"
                >
                    <i class="fas fa-shopping-cart mr-2"></i>Confirm Add to Cart
                </button>
              <button
                    wire:click="backToProduct"
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 rounded-lg font-semibold shadow-md"
                >
                    Back to Product
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-image {
        transition: transform 0.3s;
    }
    .product-image:hover {
        transform: scale(1.05);
    }
    .quantity-input {
        -moz-appearance: textfield;
    }
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endpush