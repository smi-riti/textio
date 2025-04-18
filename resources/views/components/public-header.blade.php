<!-- Navbar -->
<nav class="left-0 right-0 z-50 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
            <a href="{{route('public.home')}}" wire:navigate class="flex items-center">
                    <img src="{{ asset('assets/textio.png') }}"
                        class="h-24"
                        alt="Learn Syntax Logo">
                </a>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 mx-4 hidden md:block">
                <div class="relative max-w-lg mx-auto">
                    <input type="text" id="search-input"
                        class="w-full border-2 pl-10 pr-4 py-2 text-sm text-gray-900 bg-white rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-all duration-200"
                        placeholder="Search products, brands, categories..." aria-label="Search products">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Navigation and Cart -->
            <div class="flex items-center space-x-4">
                <!-- Hamburger Menu (Mobile) -->
                <button id="menu-toggle"
                    class="md:hidden text-white focus:outline-none p-2 rounded-lg hover:bg-purple-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/categories"
                        class="hover:text-yellow-300 font-medium transition-colors duration-200">Categories</a>
                    <a href="/deals"
                        class="hover:text-yellow-300 font-medium transition-colors duration-200">Deals</a>
                    <a href="/account"
                        class="hover:text-yellow-300 font-medium transition-colors duration-200">Account</a>
                </div>

                <!-- Cart Icon -->
                <a href="/cart"
                    class="relative hover:text-yellow-300 p-2 rounded-lg hover:bg-purple-700 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </a>
            </div>
        </div>
    </div>
</nav>