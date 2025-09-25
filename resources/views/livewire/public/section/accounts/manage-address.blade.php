<div class="container min-h-screen bg-white mx-auto p-2 md:px-4 py-6 max-w-7xl flex flex-col lg:flex-row gap-6">
    <!-- Sidebar -->
    <livewire:public.section.accounts.sidebar />

    <div class="w-full lg:w-9/12 bg-white border border-gray-200 p-4 md:p-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 pb-4 border-b border-gray-100">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-medium text-[#171717]">Manage Address</h1>
                <p class="text-gray-500 text-sm mt-1">Add, edit, or remove your delivery addresses</p>
            </div>

            <!-- Flash Message -->
            @if (session('message'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     class="flex items-center bg-green-50 text-green-700 px-4 py-2 rounded-lg border border-green-200 mb-4 sm:mb-0">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('message') }}
                </div>
            @endif

            <!-- Add Address Button -->
            <div class="flex justify-end items-center">
                <button wire:click="$dispatch('open-add')"
                        class="flex items-center text-[#8f4da7] hover:text-white border border-[#8f4da7] text-sm font-medium px-4 py-2 rounded-lg hover:bg-[#8f4da7] transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    ADD NEW ADDRESS
                </button>

                <!-- Address Update Component -->
                <livewire:public.section.accounts.address-update/>
            </div>
        </div>

        <!-- Addresses List -->
        <div class="space-y-4">
            @forelse ($addresses as $add)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-[#8f4da7] transition-colors duration-200">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            <i class="fas fa-{{ $add['address_type'] === 'home' ? 'home' : ($add['address_type'] === 'work' ? 'building' : 'map-marker-alt') }} mr-1"></i>
                            {{ ucfirst($add['address_type']) }}
                        </span>
                        <div class="flex gap-3">
                            <button wire:click="$dispatch('open-edit', { id: {{ $add['id'] }} })"
                                    class="text-gray-500 hover:text-[#8f4da7] transition-colors duration-200 p-1 rounded"
                                    title="Edit address">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button wire:click.debounce.500ms="delete({{ $add['id'] }})"
                                    class="text-gray-500 hover:text-red-600 transition-colors duration-200 p-1 rounded"
                                    title="Delete address">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6">
                            <p class="text-[#171717] font-medium text-lg">{{ $add['name'] }}</p>
                            <p class="text-gray-600 font-medium">{{ $add['phone'] }}</p>
                        </div>
                        <div class="text-gray-700">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-[#8f4da7] mt-1 mr-2 text-sm"></i>
                                <div>
                                    <p class="font-medium">{{ $add['address_line'] }}</p>
                                    {{-- @if($add['locality'])
                                        <p class="text-gray-600">{{ $add['locality'] }}</p>
                                    @endif --}}
                                    <p class="text-gray-600">{{ $add['city'] }}, {{ $add['state'] }} - {{ $add['postal_code'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Default Address Badge -->
                    @if($add['is_default'] ?? false)
                        <div class="mt-3 inline-flex items-center px-2 py-1 rounded text-xs bg-[#8f4da7] text-white">
                            <i class="fas fa-star mr-1"></i>
                            Default Address
                        </div>
                    @endif
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-12 border border-gray-200 rounded-lg">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No addresses saved</h3>
                    <p class="text-gray-500 text-sm mb-4">You haven't added any addresses yet.</p>
                    <button wire:click="$dispatch('open-add')"
                            class="inline-flex items-center text-[#8f4da7] hover:text-white border border-[#8f4da7] text-sm font-medium px-4 py-2 rounded-lg hover:bg-[#8f4da7] transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        ADD YOUR FIRST ADDRESS
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Loading State (Optional) -->
        @if($loading ?? false)
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-[#8f4da7] text-2xl"></i>
                <p class="text-gray-500 mt-2">Loading addresses...</p>
            </div>
        @endif
    </div>
    <style>
    .transition-colors {
        transition-property: color, background-color, border-color;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }

    /* Custom color classes */
    .text-\[\#171717\] {
        color: #171717;
    }

    .text-\[\#8f4da7\] {
        color: #8f4da7;
    }

    .bg-\[\#8f4da7\] {
        background-color: #8f4da7;
    }

    .border-\[\#8f4da7\] {
        border-color: #8f4da7;
    }

    .hover\:bg-\[\#8f4da7\]:hover {
        background-color: #8f4da7;
    }

    .hover\:border-\[\#8f4da7\]:hover {
        border-color: #8f4da7;
    }
</style>
</div>

