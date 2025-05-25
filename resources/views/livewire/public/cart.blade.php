<div class="flex flex-col lg:flex-row gap-6 mt-20 px-[8%]">
    <div class="flex-1 w-3/4 bg-white p-4 rounded shadow-lg">
        <h2 class="text-xl font-semibold mb-4">My Cart</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($order && $order->orderItems && $order->orderItems->count() > 0)
            @foreach ($order->orderItems as $item)
                <div class="flex items-center border-b py-2 px-6 bg-slate-100 mt-4 rounded-lg shadow-sm">
                    <div class="w-1/4">
                        <img src="{{ $item->product->image_url ?? 'https://picsum.photos/1200/400' }}"
                            alt="{{ $item->product->name }}" class="w-36 h-56 object-cover rounded mr-4">

                        <div class="flex items-center mt-4 text-bold px-8">
                            <button wire:click="updateQuantity({{ $item->id }}, 'decrement')"
                                class="bg-gray-300 px-3 py-2 rounded">-</button>
                            <span class="mx-2 text-lg">{{ $item->quantity }}</span>
                            <button wire:click="updateQuantity({{ $item->id }}, 'increment')"
                                class="bg-gray-300 px-3 py-2 rounded">+</button>
                        </div>
                    </div>

                    <div class="w-3/4">
                        <div class="flex flex-col">
                            <h3 class="font-medium text-lg">{{ $item->product->name }}</h3>
                            <p class="text-gray-600 text-sm">Brand: {{ $item->product->brand ?? 'N/A' }}</p>
                            <p class="text-gray-600 text-sm">
                                Size: {{ $item->size_variant ? $item->size_variant->name : 'N/A' }}
                            </p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span
                                    class="text-lg font-semibold">₹{{ number_format($item->product->discount_price, 2) }}</span>
                                <span
                                    class="text-sm text-gray-500 line-through">₹{{ number_format($item->product->price, 2) }}</span>
                                <span class="text-sm text-green-500">
                                    {{ round((($item->product->price - $item->product->discount_price) / $item->product->price) * 100) }}%
                                    off
                                </span>
                            </div>
                            <div class="flex items-center space-x-4 mt-2">
                                <button wire:click="saveForLater({{ $item->id }})" class="font-semibold hover:text-blue-500">
                                    SAVE FOR LATER
                                </button>
                                <button wire:click="removeItem({{ $item->id }})" class="font-semibold hover:text-red-500">
                                    REMOVE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>Your cart is empty.</p>
        @endif

        <button wire:click="placeOrder"
            class="bg-yellow-500 text-white mt-4 py-2 px-10 rounded shadow hover:bg-yellow-600 float-end">
            Place Order
        </button>
    </div>

    <div class="w-1/4 bg-white p-4 rounded shadow-lg h-96">
        <h1 class="text-gray-300 font-black border-b capitalize">PRICE DETAILS</h1>
        @php
            $totalPrice = 0;
            $totalDiscount = 0;
            $deliveryCharges = ($order && $order->orderItems->count() > 0) ? 0 : 20; // Free delivery for non-empty cart
            $packagingFee = 30; // Example packaging fee
            if ($order && $order->orderItems) {
                foreach ($order->orderItems as $item) {
                    $totalPrice += $item->product->discount_price * $item->quantity;
                    $totalDiscount += ($item->product->price - $item->product->discount_price) * $item->quantity;
                }
            }
            $totalAmount = $totalPrice + $deliveryCharges + $packagingFee;
        @endphp
        <div class="flex justify-between my-2">
            <span>Price ({{ $order && $order->orderItems ? $order->orderItems->count() : 0 }} items)</span>
            <span>₹{{ number_format($totalPrice, 2) }}</span>
        </div>
        <div class="flex justify-between my-2">
            <span>Discount</span>
            <span class="text-green-500">-₹{{ number_format($totalDiscount, 2) }}</span>
        </div>
        <div class="flex justify-between my-2">
            <span>Delivery Charges</span>
            <div class="flex gap-2">
                @if($deliveryCharges > 0)
                    <span class="line-through">₹{{ number_format($deliveryCharges, 2) }}</span>
                    <span class="text-green-500">Free</span>
                @else
                    <span class="text-green-500">Free</span>
                @endif
            </div>
        </div>
        <div class="flex justify-between my-2 mt-6">
            <span>Secured Packaging Fee</span>
            <span>₹{{ number_format($packagingFee, 2) }}</span>
        </div>
        <div class="border-t mt-6 pt-2 flex justify-between font-semibold">
            <span>Total Amount</span>
            <span>₹{{ number_format($totalAmount, 2) }}</span>
        </div>
        <div class="border-t mt-6">
            <h2 class="text-green-500 font-lg mt-4">
                You will save ₹{{ number_format($totalDiscount, 2) }} on this order
            </h2>
        </div>
    </div>
</div>