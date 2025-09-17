<div x-data="{ show: false }" 
    x-cloak
    x-show="show" 
    x-on:open-confirm.window="show = true" 
    x-on:close-confirm.window="show = false"
    class="relative z-50" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true">
    <div x-show="show" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95" 
                 @click.away="show = false"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-md border border-gray-100">
                
                <!-- Header with icon -->
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center">
                        <div class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full
                                  {{ str_contains(strtolower($title), 'delete') ? 'bg-red-100' : 'bg-purple-100' }}">
                            @if(str_contains(strtolower($title), 'delete'))
                                <svg class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            @else
                                <svg class="h-7 w-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 pb-6">
                    <div class="text-center">
                        <h3 class="text-xl font-bold leading-6 text-gray-900 mb-3" id="modal-title">{{ $title }}</h3>
                        <div class="mt-2">
                            <p class="text-gray-600 leading-relaxed">{{ $message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" 
                            wire:click="cancel" 
                            @click="show = false"
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="button" 
                            wire:click="confirm" 
                            class="inline-flex justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200
                                   {{ str_contains(strtolower($title), 'delete') ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500' }}">
                        {{ $confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <style>
[x-cloak] { display: none !important; }
</style>
</div>


