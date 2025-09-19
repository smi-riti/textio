<div class="w-full p-4 md:p-6 bg-white rounded-xl shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-gray-800 font-medium font-sans text-xl md:text-2xl">Manage Variant Values</h1>
        <button wire:click="openModal" class="px-4 py-2 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3f90] transition duration-200 shadow-md hover:shadow-lg">Add New</button>
    </div>
    
    <div class="w-full bg-white overflow-x-auto rounded-lg shadow-sm border border-gray-100">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-700">
                    <th class="p-3 text-left font-medium">S/N</th>
                    <th class="p-3 text-left font-medium">Variant Name</th>
                    <th class="p-3 text-left font-medium">Value</th>
                    <th class="p-3 text-left font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($variantValues as $index => $val)
                    <tr class="border-b hover:bg-gray-50 transition-colors duration-150">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $val->productVariant->variant_name ?? 'N/A' }}</td>
                        <td class="p-3">{{ $val->value }}</td>
                        <td class="p-3">
                            <button wire:click="deleteValue({{ $val->id }})" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 shadow-sm">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">No variant values found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-[#171717] bg-opacity-70 flex justify-center items-center z-50 p-4" wire:keydown.escape="closeModal" role="dialog" aria-modal="true">
        <div class="bg-white rounded-xl shadow-lg p-5 md:p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-medium text-gray-800 mb-4">{{ isset($editId) ? 'Edit' : 'Add' }} Variant Value</h2>
            
            <form wire:submit.prevent="save">
                <div class="mb-5">
                    <label for="product_variant_id" class="block text-gray-700 font-medium mb-2">Variant Name</label>
                    <select wire:model="product_variant_id" id="product_variant_id" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#8f4da7] shadow-sm">
                        <option value="">Select Variant Name</option>
                        @foreach ($variants as $var)
                            <option value="{{ $var->id }}">{{ $var->variant_name }}</option>
                        @endforeach
                    </select>
                    @error('product_variant_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Variant Values</label>
                    <div class="space-y-3">
                        @foreach ($values as $index => $value)
                            <div class="flex items-center gap-2">
                                <input 
                                    type="text" 
                                    wire:model="values.{{ $index }}" 
                                    placeholder="Enter Variant Value (e.g., Red, M, 8kg)" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#8f4da7] shadow-sm"
                                >
                                @if ($index > 0)
                                    <button 
                                        type="button" 
                                        wire:click="removeValue({{ $index }})" 
                                        class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors"
                                        aria-label="Remove value"
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
                    </div>
                    <button 
                        type="button" 
                        wire:click="addValue" 
                        class="mt-3 px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm"
                    >
                        + Add More
                    </button>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button 
                        type="button" 
                        wire:click="closeModal" 
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm"
                    >
                        Close
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3f90] transition duration-200 shadow-md"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>