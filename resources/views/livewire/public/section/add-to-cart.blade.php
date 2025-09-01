<div>
    @if($success)
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <button wire:click="addToCart" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Add to Cart
    </button>
</div>
