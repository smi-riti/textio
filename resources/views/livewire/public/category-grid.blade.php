<div>
    @foreach ($categories as $category)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
            <div class="p-4 bg-indigo-50">
                <img src="{{ asset('storage/' . $category->image) }}" alt="Clothing" class="mx-auto">
            </div>
            <div class="p-4 text-center">
                <h3 class="font-semibold">{{ $category->title }}</h3>
                <p class="text-sm text-gray-600">{{ $category->products_count }}</p>
            </div>
        </div>
    @endforeach
</div>
