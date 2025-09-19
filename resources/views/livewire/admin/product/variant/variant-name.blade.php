<div class="w-full p-4 md:p-6 bg-white rounded-xl shadow-sm">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200 shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-gray-800 font-medium text-xl md:text-2xl">Manage Variants</h1>
        <button wire:click='OpenModal' class="px-4 py-2 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3f90] transition duration-200 shadow-md hover:shadow-lg">
            Add Variant
        </button>
    </div>

    <!-- Variants Table -->
    <div class="w-full bg-white overflow-x-auto rounded-lg shadow-sm border border-gray-100">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-700">
                    <th class="p-3 text-left font-medium">S/N</th>
                    <th class="p-3 text-left font-medium">Variant Name</th>
                    <th class="p-3 text-left font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($variants as $index => $var)
                    <tr class="border-b hover:bg-gray-50 transition-colors duration-150">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $var->variant_name }}</td>
                        <td class="p-3">
                            <button wire:click="remove({{$var->id}})" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 shadow-sm">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-500">No variants found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if ($showModal)
    <div class="fixed inset-0 bg-[#171717] bg-opacity-70 flex justify-center items-center z-50 p-4" wire:click="CloseModal" role="dialog" aria-modal="true">
        <div class="bg-white rounded-xl shadow-lg p-5 md:p-6 w-full max-w-md" wire:click.stop>
            <h2 class="text-lg font-medium text-gray-800 mb-4">Add New Variant</h2>
            
            <form wire:submit="save" class="space-y-5">
                <div>
                    <label for="variant_name" class="block text-gray-700 font-medium mb-2">Variant Name</label>
                    <input wire:model="variant_name" type="text" id="variant_name"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#8f4da7] shadow-sm"
                        placeholder="Enter variant name">
                    @error('variant_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" wire:click='CloseModal'
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">
                        Close
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3f90] transition duration-200 shadow-md">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>