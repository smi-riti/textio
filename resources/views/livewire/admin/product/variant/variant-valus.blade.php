<div class="w-full p-6 md:p-10">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-gray-800 font-semibold font-sans text-xl md:text-2xl">Manage Variant Values</h1>
        <button wire:click="openModal" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200">Add New</button>
    </div>
    <div class="w-full bg-white overflow-x-auto ">
        <table class="w-full min-w-max ">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="p-2 text-left">S/N</th>
                    <th class="p-2 text-left">Variant Name</th>
                    <th class="p-2 text-left">Value</th>
                    <th class="p-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($variantValues as $index => $val)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $index + 1 }}</td>
                        <td class="p-2">{{ $val->productVariant->variant_name ?? 'N/A' }}</td>
                        <td class="p-2">{{ $val->value }}</td>
                        <td class="p-2">
                            <button wire:click="deleteValue({{ $val->id }})" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-2 text-center text-gray-500">No variant values found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4" wire:keydown.escape="closeModal" role="dialog" aria-modal="true">
        <form wire:submit.prevent="save" class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="mb-4">
                <label for="product_variant_id" class="block text-gray-700 font-medium mb-2">Variant Name</label>
                <select wire:model="product_variant_id" id="product_variant_id" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Select Variant Name</option>
                    @foreach ($variants as $var)
                        <option value="{{ $var->id }}">{{ $var->variant_name }}</option>
                    @endforeach
                </select>
                @error('product_variant_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Variant Values</label>
                @foreach ($values as $index => $value)
                    <div class="flex items-center mb-2">
                        <input 
                            type="text" 
                            wire:model="values.{{ $index }}" 
                            placeholder="Enter Variant Value (e.g., Red, M, 8kg)" 
                            class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                        >
                        @if ($index > 0)
                            <button 
                                type="button" 
                                wire:click="removeValue({{ $index }})" 
                                class="ml-2 text-red-500 hover:text-red-700"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                    @error("values.{$index}")
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @endforeach
                <button 
                    type="button" 
                    wire:click="addValue" 
                    class="mt-2 px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200"
                >
                    Add More
                </button>
            </div>

            <div class="flex justify-end space-x-3">
                <button 
                    type="button" 
                    wire:click="closeModal" 
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                >
                    Close
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200"
                >
                    Save
                </button>
            </div>
        </form>
    </div>
    @endif
</div>