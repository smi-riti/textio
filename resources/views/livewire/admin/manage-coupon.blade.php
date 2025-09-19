<div>
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-4 flex justify-between items-center transition-opacity duration-300"
             role="alert">
            {{ session('message') }}
            <button type="button" class="text-green-700 hover:text-green-900" data-bs-dismiss="alert" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <!-- Search and Add Button -->
    <div class="flex flex-wrap gap-3 mb-4 p-3 justify-between">
        <div class="flex items-center w-full sm:w-auto">
            <input type="text" class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary"
                   placeholder="Search by coupon code" wire:model.live="search">
            <span class="px-4 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md">
                <i class="bi bi-search"></i>
            </span>
        </div>
        <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#7a3d92] transition-colors"
                wire:click="openModal">
            Add Coupon
        </button>
    </div>

    <!-- Coupons Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($coupons as $coupon)
            <div>
                <div class="bg-white shadow-sm rounded-lg h-full flex flex-col">
                    <div class="p-4 flex-1">
                        <h5 class="text-lg font-semibold text-dark">{{ $coupon->code }}</h5>
                        <div class="text-sm text-gray-600 mt-2">
                            <p><strong>Type:</strong> {{ Str::title($coupon->discount_type) }}</p>
                            @if ($coupon->discount_type !== 'freeShipping')
                                <p><strong>Value:</strong> {{ $coupon->discount_value }}
                                    {{ $coupon->discount_type === 'percentage' ? '%' : '₹' }}</p>
                            @endif
                            <div class="flex gap-4 mt-2">
                                <div>
                                    <strong>Start:</strong>
                                    {{ $coupon->start_date ? $coupon->start_date->format('M d, Y') : 'Not Set' }}
                                </div>
                                <div>
                                    <strong>Expires:</strong>
                                    {{ $coupon->expiration_date ? $coupon->expiration_date->format('M d, Y') : 'No Expiry' }}
                                </div>
                            </div>
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded {{ $coupon->status ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $coupon->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-200 flex justify-between">
                        <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition-colors text-sm"
                                wire:click="edit({{ $coupon->id }})">
                            Edit
                        </button>
                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition-colors text-sm"
                                wire:click="delete({{ $coupon->id }})"
                                onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div>
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg">
                    No coupons found.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Coupon Modal -->
    <div class="{{ $showModal ? 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50' : 'hidden' }}"
         tabindex="-1">
        <div class="bg-white rounded-lg w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h5 class="text-lg font-semibold text-dark">{{ $isEditMode ? 'Edit Coupon' : 'Add Coupon' }}</h5>
                <button type="button" class="text-dark hover:text-gray-700" wire:click="closeModal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="p-4">
                <form>
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700">Coupon Code</label>
                        <input type="text"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary @error('code') border-red-500 @enderror"
                               id="code" wire:model.live="code">
                        @error('code')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="minimum_purchase_amount" class="block text-sm font-medium text-gray-700">Minimum Purchase Amount</label>
                        <input type="text"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary @error('minimum_purchase_amount') border-red-500 @enderror"
                               id="minimum_purchase_amount" wire:model.live="minimum_purchase_amount">
                        @error('minimum_purchase_amount')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="discount_type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary @error('discount_type') border-red-500 @enderror"
                                id="discount_type" wire:model.live="discount_type">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                            <option value="freeShipping">Free Shipping</option>
                        </select>
                        @error('discount_type')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($discount_type !== 'freeShipping')
                        <div class="mb-4">
                            <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount Value</label>
                            <div class="flex">
                                <input type="number" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary @error('discount_value') border-red-500 @enderror"
                                       id="discount_value" wire:model.live="discount_value">
                                <span class="px-4 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md">
                                    {{ $discount_type === 'percentage' ? '%' : '₹' }}
                                </span>
                            </div>
                            @error('discount_value')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary @error('start_date') border-red-500 @enderror"
                               id="start_date" wire:model.live="start_date">
                        @error('start_date')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                        <input type="date"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary @error('expiration_date') border-red-500 @enderror"
                               id="expiration_date" wire:model.live="expiration_date">
                        @error('expiration_date')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 flex items-center">
                        <input type="checkbox"
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                               id="status" wire:model.live="status">
                        <label for="status" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 flex justify-end gap-2">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors"
                        wire:click="closeModal">
                    Close
                </button>
                <button type="button" class="bg-primary text-white px-4 py-2 rounded hover:bg-[#7a3d92] transition-colors"
                        wire:click="save">
                    <span wire:loading wire:target="save" class="inline-block h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    {{ $isEditMode ? 'Update' : 'Save' }}
                </button>
            </div>
        </div>
    </div>
</div>