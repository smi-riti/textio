<div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">PRICE DETAILS</h2>

    <div class="space-y-3">
        <div class="flex justify-between">
            <span>Price ({{ $quantity }} item{{ $quantity > 1 ? 's' : '' }})</span>
            <span>₹{{ number_format($price * $quantity) }}</span>
        </div>

        @if($discount > 0)
        <div class="flex justify-between">
            <span>Discount</span>
            <span class="text-green-600">-₹{{ number_format($totalDiscount) }}</span>
        </div>
        @endif

        <div class="flex justify-between">
            <span>Delivery Charges</span>
            @if($deliveryCharge > 0)
                <span>₹{{ number_format($deliveryCharge) }}</span>
            @else
                <span class="text-green-600">FREE</span>
            @endif
        </div>

        <div class="flex justify-between text-lg font-semibold border-t pt-3 mt-3">
            <span>Total Amount</span>
            <span>₹{{ number_format($totalPrice) }}</span>
        </div>
    </div>

    @if($discount > 0)
    <div class="mt-4 text-green-600 font-semibold">
        You will save ₹{{ number_format($totalDiscount) }} on this order
    </div>
    @endif

    <div class="mt-6 p-3 bg-green-50 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-shield-alt text-green-600 mt-1 mr-2"></i>
            <div>
                <p class="text-sm font-semibold">Safe and Secure Payments</p>
                <p class="text-xs text-gray-600">Easy returns. 100% Authentic products.</p>
            </div>
        </div>
    </div>

    <div class="mt-6 space-y-3">
        <div class="flex items-center">
            <i class="fas fa-undo-alt text-gray-500 mr-2"></i>
            <span class="text-sm">14 Days Return Policy</span>
        </div>
        <div class="flex items-center">
            <i class="fas fa-shield-alt text-gray-500 mr-2"></i>
            <span class="text-sm">Warranty Available</span>
        </div>
        <div class="flex items-center">
            <i class="fas fa-truck text-gray-500 mr-2"></i>
            <span class="text-sm">Free Delivery on orders above ₹499</span>
        </div>
    </div>
</div>