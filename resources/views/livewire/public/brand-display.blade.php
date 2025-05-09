<section class="bg-white py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center">Shop by Brand</h2>
        <div class="flex flex-wrap justify-center items-center gap-8">
            {{-- @dd($brands) --}}
            @foreach ($brands as $brand)
                <div class="grayscale hover:grayscale-0 transition p-4">
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="Brand" class="h-10">

                </div>
                
            @endforeach
            
           
        </div>
    </div>
</section>