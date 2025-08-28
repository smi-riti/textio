<!-- Navbar -->
{{-- <nav class="left-0 right-0 z-50 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{route('public.home')}}" wire:navigate class="flex items-center">
                    <img src="{{ asset('assets/textio.png') }}" class="h-24" alt="Learn Syntax Logo">
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
                @auth
                    @if (Auth::user()->isAdmin)
                        <a wire:navigate href="{{ route('admin') }}" wire:navigate
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 active:scale-95 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="hidden md:flex">Admin Panel</span>
                        </a>
                    @else
                        <div class="relative"
                            x-data="{ open: false, fullName: '{{ Auth::user()->name }}', shortName: '{{ substr(Auth::user()->name, 0, 2) }}' }">
                            <button @click="open = !open" @click.away="open = false"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <span class="md:inline hidden" x-text="fullName"></span>
                                <!-- Full name on medium screens and up -->
                                <span class="md:hidden" x-text="shortName"></span> <!-- First 2 chars on mobile -->
                                <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open"
                                class="absolute right-0 w-48 mt-2 origin-top-right bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="py-1">                                   
                                    <button wire:click="logout"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        Logout
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif                    
                @endauth
                @guest
                        <a href="{{ route('login') }}" wire:navigate
                            class="relative inline-flex items-center px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 rounded-lg hover:from-blue-700 hover:via-indigo-700 hover:to-blue-800 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 shadow-md hover:shadow-xl group overflow-hidden">
                            <span class="relative z-10">Sign-In</span>
                            <span
                                class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-gradient"></span>
                            <svg class="hidden sm:block w-4 sm:w-5 h-4 sm:h-5 ml-2 -mr-1 transition-transform duration-300 transform group-hover:translate-x-1"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endguest

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
</nav> --}}
 <!-- Top Bar -->
 {{-- <header class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <!-- Middle Header -->
        <div class="py-4 flex items-center justify-between flex-wrap">
            <a href="#" class="text-2xl sm:text-3xl font-bold text-indigo-600">Tex<span class="text-gray-800">tio</span></a>
            
            <div class="w-full max-w-xl px-4 hidden md:block">
                <div class="relative">
                    <input type="text" placeholder="Search for products..." class="w-full py-2 pl-4 pr-10 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500">
                    <button class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-indigo-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <div class="flex items-center space-x-4 md:space-x-6">
                <button class="md:hidden" x-on:click="mobileMenuOpen = !mobileMenuOpen">
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
                            <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">Sign In</a>
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
                <input type="text" placeholder="Search for products..." class="w-full py-2 pl-4 pr-10 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500">
                <button class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-indigo-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

</header> --}}