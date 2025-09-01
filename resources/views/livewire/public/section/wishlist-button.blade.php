<button wire:click="toggleWishlist" class="p-2 rounded-full bg-white transition-colors {{ $isInWishlist ? 'text-red-500' : 'text-gray-200' }}">
    <svg class="w-5 h-5 mr-1" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
    </svg>
</button>