<header class="bg-white/85 backdrop-blur-sm  border-b border-gray-200 sticky top-0 z-50" wire:init="resetMobileMenu">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-3 flex items-center justify-between">
            <a class="flex items-center" href="/" wire:navigate>
                <span class="text-2xl font-bold tracking-tight text-[#171717]">TEXTIO</span>
            </a>

            <!-- Desktop Navigation and Search -->
            <div class="hidden md:flex items-center space-x-6">
                <nav class="flex space-x-6">
                    <a wire:navigate href="{{ route('about') }}"
                        class="text-[#171717] hover:text-[#8f4da7] font-medium text-sm transition-colors duration-200">About
                        Us</a>
                    <a wire:navigate href="{{ route('contact') }}"
                        class="text-[#171717] hover:text-[#8f4da7] font-medium text-sm transition-colors duration-200">Contact
                        Us</a>
                    <a wire:navigate href="{{ route('public.product.all') }}"
                        class="text-[#171717] hover:text-[#8f4da7] font-medium text-sm transition-colors duration-200">Browse</a>
                </nav>
                {{-- <form wire:submit.prevent="search" class="relative group">
                    <input wire:model.debounce.500ms="searchQuery" type="text" placeholder="Search products..." class="w-48 px-4 py-1.5 text-[#171717] text-sm bg-gray-100 rounded-full focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8f4da7] transition-all duration-300 ease-in-out group-hover:w-56">
                    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[#171717] group-hover:text-[#8f4da7] transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form> --}}
            </div>

            <!-- Right Side Icons -->
            <div class="flex items-center space-x-3">
                @auth
                    <div x-data="{ cart: @entangle('cartCount') }" class="relative">
                        <a wire:navigate href="{{ route('myCart') }}"
                            class="flex items-center text-[#171717] hover:text-[#8f4da7] transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            <span x-show="cart > 0" x-text="cart"
                                class="absolute -top-1 -right-1 bg-[#8f4da7] text-white text-xs font-medium rounded-full w-4 h-4 flex items-center justify-center"></span>
                        </a>
                    </div>
                    <div x-data="{ wishlist: @entangle('wishlistCount') }" class="relative hidden md:flex">
                        <a wire:navigate href="{{ route('wishlist.index') }}"
                            class="flex items-center text-[#171717] hover:text-[#8f4da7] transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 016.364 6.364l-7.682 7.682a.75.75 0 01-1.06 0l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                            </svg>
                            <span x-show="wishlist > 0" x-text="wishlist"
                                class="absolute -top-1 -right-1 bg-[#8f4da7] text-white text-xs font-medium rounded-full w-4 h-4 flex items-center justify-center"></span>
                        </a>
                    </div>
                @endauth

                <div x-data="{ accountDropdownOpen: false }" class="relative">
                    <button @click="accountDropdownOpen = !accountDropdownOpen"
                        class="flex items-center text-[#171717] hover:text-[#8f4da7] transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </button>
                    <div x-show="accountDropdownOpen" @click.outside="accountDropdownOpen = false" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white shadow-md rounded-md z-50 overflow-hidden">
                        @auth
                            <a wire:navigate href="{{ route('myOrders') }}"
                                class="block px-4 py-2 text-[#171717] hover:bg-gray-100 hover:text-[#8f4da7] transition-colors duration-200">My
                                Orders</a>
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-[#171717] hover:bg-gray-100 hover:text-[#8f4da7] transition-colors duration-200">Logout</button>
                            </form>
                        @endauth
                        @guest
                            <a wire:navigate href="{{ route('login') }}"
                                class="block px-4 py-2 text-[#171717] hover:bg-gray-100 hover:text-[#8f4da7] transition-colors duration-200">Sign
                                In</a>
                            <a wire:navigate href="{{ route('register') }}"
                                class="block px-4 py-2 text-[#171717] hover:bg-gray-100 hover:text-[#8f4da7] transition-colors duration-200">Register</a>
                        @endguest
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button @click="$wire.toggleMobileMenu(); mobileMenuOpen = $wire.mobileMenuOpen"
                    class="md:hidden text-[#171717] hover:text-[#8f4da7] transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            x-show="!$wire.mobileMenuOpen" d="M4 6h16M4 12h16m-7 6h7" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            x-show="$wire.mobileMenuOpen" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="$wire.mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4" class="md:hidden py-4 border-t border-gray-200">

            <nav class="flex flex-col space-y-3">
                <a wire:navigate href="#"
                    class="text-[#171717] hover:text-[#8f4da7] font-medium text-sm transition-colors duration-200">About
                    Us</a>
                <a wire:navigate href="#"
                    class="text-[#171717] hover:text-[#8f4da7] font-medium text-sm transition-colors duration-200">Contact
                    Us</a>
                <a wire:navigate href="#"
                    class="text-[#171717] hover:text-[#8f4da7] font-medium text-sm transition-colors duration-200">Browse</a>
            </nav>
            <div class="mt-4 space-y-2">
                @auth


                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full py-2 bg-[#8f4da7] text-white rounded-md font-medium text-sm hover:bg-[#171717] transition-colors duration-200">Logout</button>
                    </form>
                @endauth
                @guest
                    <a wire:navigate href="{{ route('login') }}"
                        class="block w-full py-2 bg-[#8f4da7] text-white rounded-md font-medium text-sm text-center hover:bg-[#171717] transition-colors duration-200">Login</a>
                    <a wire:navigate href="{{ route('register') }}"
                        class="block w-full py-2 bg-[#171717] text-white rounded-md font-medium text-sm text-center hover:bg-[#8f4da7] transition-colors duration-200">Register</a>
                @endguest
            </div>
        </div>
    </div>
</header>
