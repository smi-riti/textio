<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        @if ($categories->isNotEmpty())
            <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">Popular Categories</h2>

            <div x-data="categoriesScroller()" x-init="init()">
                <div class="relative">
                    <!-- Carousel Container -->
                    <div class="overflow-hidden">
                        <div x-ref="container"
                            class="flex transition-transform duration-500 ease-in-out scrollbar-hide snap-x snap-mandatory scroll-smooth"
                            style="overflow-x: auto;">
                            @foreach ($categories as $category)
                                <div class="category-card group flex-shrink-0 w-32 md:w-40 px-2 snap-center">
                                    {{-- <a href="{{ route('categories.view', $category->slug) }}"> --}}
                                        <a href="">
                                        <div class="relative overflow-hidden rounded-md bg-purple-100 h-24 md:h-28">
                                            <img src="{{ $category->image_url ?? 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1480&q=80' }}"
                                                alt="{{ $category->title }}"
                                                class="w-full h-full object-cover group-hover:brightness-110 transition duration-300">
                                            <div class="absolute inset-0 mt-5  bg-opacity-50 flex items-center justify-center">
                                                <h3 class="text-gray-800 text-base md:text-lg font-semibold text-center">{{ $category->title }}</h3>
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
                                'bg-gray-400 cursor-not-allowed': scroll <= 0,
                                'bg-purple-900 hover:bg-purple-800': scroll > 0
                            }"
                            class="absolute left-0 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full transition duration-300"
                            aria-label="Previous slide">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button @click="scrollNext" :disabled="scroll >= scrollMax"
                            :class="{
                                'bg-gray-400 cursor-not-allowed': scroll >= scrollMax,
                                'bg-purple-900 hover:bg-purple-800': scroll < scrollMax
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