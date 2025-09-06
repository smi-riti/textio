<div class="container min-h-screen bg-white mx-auto p-2 md:px-4 py-6 max-w-7xl flex flex-col lg:flex-row gap-6">
    <!-- Sidebar -->
    <livewire:public.section.accounts.sidebar />

    <div class="w-full lg:w-9/12 shadow bg-gray-50 md:p-4">
        <div class="flex w-full text-center  justify-between items-center">
            <h1 class="text-xl text-gray-800 ">Manage Address</h1>

        <!-- Flash Message -->
        @if (session('message'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 x-init="setTimeout(() => show = false, 3000)"
                 class="bg-green-100 text-green-700 p-3 rounded-md mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- Add Address Button -->
       <div class="flex justify-end items-center">
         <button wire:click="$dispatch('open-add')"
                class="border border-purple-200 text-purple-600  text-sm font-medium hover:text-white px-4 py-2 rounded hover:bg-purple-700 transition-colors duration-200">
                <i class="fas fa-plus text-sm white"></i>
            ADD A NEW ADDRESS
        </button>

        <!-- Address Update Component -->
        <livewire:public.section.accounts.address-upadate />
       </div>
        </div>

        @forelse ($addresses as $add)
            <div class="border border-purple-200 rounded-md p-4 mt-4">
                <div class="flex justify-between">
                    <p class="text-gray-500 px-2 py-1 rounded text-sm bg-gray-200 ">{{ ucfirst($add['address_type']) }}</p>
                     <div class="flex gap-4">
                        <button wire:click="$dispatch('open-edit', { id: {{ $add['id'] }} })"
                                class="text-purple-600 hover:text-purple-800 font-medium transition-colors duration-200">
                           <i class="fas fa-edit text-sm"></i>

                        </button>
                        <button wire:click.debounce.500ms="delete({{ $add['id'] }})"
                                class="text-red-600 hover:text-red-800 font-medium transition-colors duration-200">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col space-y-1 mt-2">
                    <div class="flex flex-col sm:flex-row sm:space-x-4">
                        <p class="text-gray-900 font-medium">{{ $add['name'] }}</p>
                        <p class="text-gray-900 font-medium">{{ $add['phone'] }}</p>
                    </div>
                    <div class="text-gray-600 text-sm">
                        {{ $add['address_line'] }}, {{ $add['locality'] ?? '' }}, {{ $add['city'] }}, {{ $add['state'] }} -
                        {{ $add['postal_code'] }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 mt-4">No addresses found.</p>
        @endforelse
    </div>
</div>