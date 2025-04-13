<nav
    class="flex justify-between items-center py-2 px-6 bg-white shadow w-full rounded-full">
    <div class="flex items-center justify-start w-full">
        <div class="flex flex-row items-center">
            <!-- Hamburger button (only on small screens) -->
            <button @click="sidebarOpen = !sidebarOpen" class=" p-2 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>

            </button>

            <!-- Logo (visible on larger screens) -->
            <a class="text-gray-600" href="">Textio</a>
        </div>
    </div>
    </div>

    <div class="sm:mb-0 self-center ml-auto relative">
        
            <div x-data="{ isOpen: false }" class="relative">
                <button @click="isOpen = !isOpen" 
                        @click.away="isOpen = false"
                        type="button" 
                        class="relative inline-flex items-center p-2 hover:bg-gray-100 rounded-full"
                        id="user-menu-button">
                        
                        <img class="w-8 h-8 rounded-full" src="{{ session('user_avatar') }}" alt="User Photo">
                </button>

                <div x-show="isOpen"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                    <div class="py-2 px-4 text-sm text-gray-700">
                    <span class="block font-semibold truncate max-w-[200px]">sa</span>
                    </div>
                    
                </div>
            </div>
      
    </div>
</nav>