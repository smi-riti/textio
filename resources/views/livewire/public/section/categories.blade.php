<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        @if ($categories->isNotEmpty())
            <h2 class="text-3xl font-semibold text-center mb-12">Popular Categories</h2>

            <div x-data="categoriesScroller()" x-init="init()">
                <div class="relative">
                    <!-- Carousel Container -->
                    <div class="overflow-hidden">
                        <div x-ref="container"
                            class="flex transition-transform duration-500 ease-in-out scrollbar-hide snap-x snap-mandatory scroll-smooth"
                            style="overflow-x: auto;">
                            @foreach ($categories as $category)
                                <div class="category-card group flex-shrink-0 w-full md:w-1/4 px-2 snap-center">
                                    {{-- <a href="{{ route('categories.view', $category->slug) }}"> --}}
                                        <a href="">
                                        <div class="relative overflow-hidden rounded-lg bg-gray-100 h-48 md:h-64">
                                            <img src="{{ $category->image_url ?? 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1480&q=80' }}"
                                                alt="{{ $category->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                                <h3 class="text-white text-xl font-semibold">{{ $category->title }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Navigation Buttons (Hidden on Mobile) -->
                    <div class="hidden md:block" x-show="scrollMax > 0">
                        <button @click="scrollPrev" :disabled="scroll <= 0"
                            :class="{
                                'bg-gray-400 cursor-not-allowed': scroll <=
                                    0,
                                'bg-purple-600 hover:bg-purple-700': scroll > 0
                            }"
                            class="absolute left-0 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full transition duration-300"
                            aria-label="Previous slide">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button @click="scrollNext" :disabled="scroll >= scrollMax"
                            :class="{
                                'bg-gray-400 cursor-not-allowed': scroll >=
                                    scrollMax,
                                'bg-purple-600 hover:bg-purple-700': scroll < scrollMax
                            }"
                            class="absolute right-0 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full transition duration-300"
                            aria-label="Next slide">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>

    <script>
        window.categoriesScroller = window.categoriesScroller || function() {
            return {
                scroll: 0,
                scrollMax: 0,
                itemWidth: 0,
                containerWidth: 0,
                init() {
                    queueMicrotask(() => this.calculateWidths());
                    window.addEventListener('resize', () => queueMicrotask(() => this.calculateWidths()));
                },
                calculateWidths() {
                    if (!this.$refs.container) return;
                    this.containerWidth = this.$refs.container.clientWidth;
                    const first = this.$refs.container.children[0];
                    this.itemWidth = (first ? first.offsetWidth : 0) + 4; // Adjust for px-2 (4px total padding)
                    this.scrollMax = Math.max(0, this.$refs.container.scrollWidth - this.containerWidth);
                },
                scrollNext() {
                    this.scroll = Math.min(this.scroll + this.itemWidth, this.scrollMax);
                    this.$refs.container.scrollTo({
                        left: this.scroll,
                        behavior: 'smooth'
                    });
                },
                scrollPrev() {
                    this.scroll = Math.max(this.scroll - this.itemWidth, 0);
                    this.$refs.container.scrollTo({
                        left: this.scroll,
                        behavior: 'smooth'
                    });
                },
            };
        };
    </script>
</section>
