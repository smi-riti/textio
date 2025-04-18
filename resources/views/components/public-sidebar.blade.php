<div>
    <div id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out">
        <div class="p-4">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between mb-6">
                <img src="assets/ShopWave.png" class="h-8" alt="ShopWave Logo">
                <button id="close-sidebar" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Search -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" id="mobile-search-input"
                        class="w-full pl-10 pr-4 py-2 text-sm text-gray-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Search products..." aria-label="Search products">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <nav class="space-y-3">
                <a href="/"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
                <a href="/categories"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Categories
                </a>
                <a href="/deals"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Deals
                </a>
                <a href="/account"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Account
                </a>
                <a href="/cart"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Cart
                </a>
            </nav>
        </div>
    </div>

</div>

    <script>
        // Toggle mobile sidebar
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.body.style.overflow = 'hidden';
        });

        document.getElementById('close-sidebar').addEventListener('click', function () {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.body.style.overflow = '';
        });

        // Basic search functionality
        const searchInputs = [document.getElementById('search-input'), document.getElementById('mobile-search-input')];
        searchInputs.forEach(input => {
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter' && this.value.trim()) {
                    const query = this.value.trim();
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            });
        });
    </script>