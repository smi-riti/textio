<div class="container mx-auto p-6">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <button wire:click='OpenModal'
        class="bg-orange-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-orange-600 transition duration-300">
        Add Variant
    </button>

    <table class="min-w-full mt-6 bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-orange-500 text-white">
            <tr>
                <th class="py-3 px-4 text-left">S/N</th>
                <th class="py-3 px-4 text-left">Variant Name</th>
                <th class="py-3 px-4 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @forelse ($variants as $index => $var)
                <tr class="border-b hover:bg-orange-50">
                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                    <td class="py-3 px-4">{{ $var->variant_name }}</td>
                    <td class="py-3 px-4">
                        <button wire:click="remove({{$var->id}})"
                            class="text-red-600 px-3 py-1 border">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-3 px-4 text-center text-gray-500">No variants found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

   @if ($showModal)
    <div wire:click="CloseModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div wire:click.stop class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label for="variant_name" class="block text-sm font-medium text-gray-700">Variant Name</label>
                    <input wire:model="variant_name" type="text"
                        class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Variant Name">
                    @error('variant_name')
                        <p class="mt-1 text-sm text-orange-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="submit"
                        class="bg-orange-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-orange-600 transition duration-300">
                        Add
                    </button>
                    <button wire:click='CloseModal'
                        class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-300">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
</div>
