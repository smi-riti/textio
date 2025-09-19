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
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h2 class="text-xl text-purple-700 mb-4">
                {{ $address_id ? 'Edit Address' : 'Add New Address' }}
            </h2>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" wire:model.live="name" placeholder="Enter full name"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model.live="phone" placeholder="10-digit mobile number"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" 
                               wire:model.live="postal_code" 
                               placeholder="Pincode (6 digits)" 
                               maxlength="6"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('postal_code') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                    <div>
                        <input type="text" 
                               wire:model.live="address_line" 
                               placeholder="Locality"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('address_line') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <textarea wire:model.live="area" placeholder="Area" rows="3"
                              class="w-full border border-purple-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    @error('area') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" 
                               wire:model.live="city" 
                               placeholder="City"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="text" 
                               wire:model.live="state" 
                               placeholder="State"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('state') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" wire:model.live="landmark" placeholder="Landmark (optional)"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('landmark') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model.live="alt_phone" placeholder="Alternate phone (optional)"
                               class="border border-purple-300 rounded-md px-3 py-2 text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('alt_phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-purple-600 mb-2 block">Address Type</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" wire:model.live="address_type" value="home">
                            <span class="text-gray-700">Home</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" wire:model.live="address_type" value="office">
                            <span class="text-gray-700">Office</span>
                        </label>
                    </div>
                    @error('address_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open = false"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors duration-200">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>