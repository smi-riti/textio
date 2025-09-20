<div>
    <!-- Modal -->
    <div x-data="{ open: false }"
         x-show="open"
         x-cloak
         @open-add.window="open = true; $wire.resetForm()"
         @open-edit.window="open = true; $wire.edit($event.detail.id)"
         @close-modal.window="open = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 sm:p-6">
        <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-4xl mx-4 sm:mx-6 lg:mx-auto max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center text-center">
                 <h2 class="text-lg sm:text-xl text-purple-700 mb-4">
                {{ $address_id ? 'Edit Address' : 'Add New Address' }}
               
            </h2>
             <button  @click="open = false">
                
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5" viewBox="0 0 640 640"><path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/></svg>
             </button>
            </div>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" 
                               wire:model.live="name" 
                               placeholder="Enter full name"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base">
                        @error('name') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="text" 
                               wire:model.live="phone" 
                               placeholder="10-digit mobile number"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base">
                        @error('phone') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" 
                               wire:model.live="postal_code" 
                               placeholder="Pincode (6 digits)" 
                               maxlength="6"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base">
                        @error('postal_code') 
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                    <div>
                        <input type="text" 
                               wire:model.live="address_line" 
                               placeholder="Locality"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base">
                        @error('address_line') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <textarea wire:model.live="area" 
                              placeholder="Area" 
                              rows="3"
                              class="w-full border border-purple-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base"></textarea>
                    @error('area') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" 
                               wire:model.live="city" 
                               placeholder="City"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base">
                        @error('city') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="text" 
                               wire:model.live="state" 
                               placeholder="State"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base">
                        @error('state') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" 
                               wire:model.live="landmark" 
                               placeholder="Landmark (optional)"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base">
                        @error('landmark') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="text" 
                               wire:model.live="alt_phone" 
                               placeholder="Alternate phone (optional)"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm sm:text-base">
                        @error('alt_phone') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-purple-600 mb-2 block text-sm sm:text-base">Address Type</label>
                    <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                        <label class="flex items-center space-x-2">
                            <input type="radio" 
                                   wire:model.live="address_type" 
                                   value="home"
                                   class="h-4 w-4 sm:h-5 sm:w-5">
                            <span class="text-gray-700 text-sm sm:text-base">Home</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" 
                                   wire:model.live="address_type" 
                                   value="office"
                                   class="h-4 w-4 sm:h-5 sm:w-5">
                            <span class="text-gray-700 text-sm sm:text-base">Office</span>
                        </label>
                    </div>
                    @error('address_type') <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                    <button type="button" 
                            @click="open = false"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors duration-200 text-sm sm:text-base">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors duration-200 text-sm sm:text-base">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>