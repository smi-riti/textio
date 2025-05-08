<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopWave</title>
    <meta name="description" content="ShopWave E-commerce Platform">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <!-- Middle Header -->
            <div class="py-4 flex items-center justify-between flex-wrap">
                <a href="#" class="text-2xl sm:text-3xl font-bold text-indigo-600">Tex<span class="text-gray-800">tio</span></a>
                
                <div class="w-full max-w-xl px-4 hidden md:block">
                    <div class="relative">
                        <input type="text" id="desktop-search-input" placeholder="Search for products..." class="w-full py-2 pl-4 pr-10 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500">
                        <button class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-indigo-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 md:space-x-6">
                    <button id="menu-toggle" class="md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="hidden md:flex items-center space-x-4 md:space-x-6">
                        <a href="#" class="flex items-center hover:text-indigo-600">
                            <i class="far fa-heart text-xl"></i>
                            <span class="ml-1">Wishlist</span>
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center hover:text-indigo-600">
                                <i class="far fa-user text-xl"></i>
                                <span class="ml-1">Account</span>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 py-2 w-48 bg-white shadow-lg rounded-lg z-40">
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Sign In</a>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Register</a>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                            </div>
                        </div>
                        <div class="relative">
                            <a href="#" class="flex items-center hover:text-indigo-600">
                                <i class="fas fa-shopping-cart text-xl"></i>
                                <span class="ml-1">Cart</span>
                                <span class="ml-1 bg-indigo-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Search -->
            <div class="w-full px-4 md:hidden mb-4">
                <div class="relative">
                    <input type="text" id="mobile-search-input" placeholder="Search for products..." class="w-full py-2 pl-4 pr-10 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500">
                    <button class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-indigo-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:-translate-x-full">
        <div class="p-4">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between mb-6">
                <img src="assets/ShopWave.png" class="h-8" alt="ShopWave Logo">
                <button id="close-sidebar" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Search -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" id="mobile-search-input-sidebar" class="w-full pl-10 pr-4 py-2 text-sm text-gray-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Search products..." aria-label="Search products">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <nav class="space-y-3">
                <a href="/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
                <a href="/categories" class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Categories
                </a>
                <a href="/deals" class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Deals
                </a>
                <a href="/account" class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Account
                </a>
                <a href="/cart" class="flex items-center px-4 py-2 text-gray-700 hover:bg-purple-100 hover:text-purple-600 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Cart
                </a>
            </nav>
        </div>
    </div>

    <div class="flex-grow">
       {{ $slot }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menu-toggle');
            const closeSidebar = document.getElementById('close-sidebar');

            // Toggle sidebar on menu button click
            if (menuToggle) {
                menuToggle.addEventListener('click', function () {
                    sidebar.classList.remove('-translate-x-full');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Close sidebar
            if (closeSidebar) {
                closeSidebar.addEventListener('click', function () {
                    sidebar.classList.add('-translate-x-full');
                    document.body.style.overflow = '';
                });
            }

            // Close sidebar on resize to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768) {
                    sidebar.classList.add('-translate-x-full');
                    document.body.style.overflow = '';
                }
            });

            // Basic search functionality
            const searchInputs = [
                document.getElementById('desktop-search-input'),
                document.getElementById('mobile-search-input'),
                document.getElementById('mobile-search-input-sidebar')
            ];
            searchInputs.forEach(input => {
                if (input) {
                    input.addEventListener('keypress', function (e) {
                        if (e.key === 'Enter' && this.value.trim()) {
                            const query = this.value.trim();
                            window.location.href = `/search?q=${encodeURIComponent(query)}`;
                        }
                    });
                }
            });

            // SweetAlert2 for session messages
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 is not loaded.');
                return;
            }

            // Example session messages (replace with actual session logic if using Laravel)
            /*
            Swal.fire({
                title: 'Success!',
                text: 'Your action was successful!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#662d91'
            });
            */
        });
    </script>
</body>
</html>