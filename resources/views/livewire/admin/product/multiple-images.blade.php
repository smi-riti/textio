
<div>
    <h2 class="text-lg font-bold mb-4">Manage Product Images</h2>

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    {{-- Upload New Image --}}
    <form wire:submit.prevent="update" class="mb-4">
        <div class="flex items-center space-x-4">
            <input type="file" wire:model="photo" class="border p-2 rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Upload Image
            </button>
        </div>
        @error('photo') 
            <span class="text-red-500 text-sm">{{ $message }}</span> 
        @enderror
    </form>

    {{-- Display Uploaded Images --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach ($productImages as $image)
            <div class="relative border rounded p-2">
                <img src="{{ $image->image_path }}" alt="Product Image" class="w-full h-32 object-cover rounded">
                <button wire:click="deleteImage({{ $image->id }})" class="absolute top-2 right-2 bg-red-500 text-white text-sm px-2 py-1 rounded hover:bg-red-600">
                    Delete
                </button>
            </div>
        @endforeach
    </div>
</div>