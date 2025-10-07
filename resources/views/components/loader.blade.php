<div {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center bg-black/60']) }} x-cloak x-show="isLoading">
    <div class="text-center">
        <div class="inline-block w-16 h-16 border-4 border-white border-t-transparent rounded-full animate-spin mb-4" aria-hidden="true"></div>
        <div class="text-white font-semibold text-lg animate-pulse">
            Textio
        </div>
    </div>
</div>