
<div>
    <form wire:submit.prevent="uploadImage">
        @if (session()->has('message'))
            <div class="alert alert-success mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">
                Upload Image
            </label>
            <input 
                type="file" 
                wire:model="image" 
                id="image"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                accept="image/*"
            >
            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button 
            type="submit" 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            wire:loading.attr="disabled"
            wire:target="uploadImage"
        >
            <span wire:loading.remove wire:target="uploadImage">Upload Image</span>
            <span wire:loading wire:target="uploadImage">Uploading...</span>
        </button>

        @if ($isUploading)
            <div class="mt-4">
                <p>Uploading image...</p>
            </div>
        @endif

        @if ($uploadedImageUrl)
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-2">Uploaded Image:</h3>
                <img 
                    src="{{ $uploadedImageUrl }}" 
                    alt="Uploaded image" 
                    class="max-w-full h-auto rounded shadow-md"
                    style="max-height: 300px;"
                >
                <div class="mt-2 text-sm text-gray-600">
                    <p>File ID: {{ $uploadedImage->fileId }}</p>
                    <p>URL: <a href="{{ $uploadedImageUrl }}" target="_blank" class="text-blue-500">View Image</a></p>
                </div>
            </div>
        @endif
    </form>
</div>