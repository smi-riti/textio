<!-- resources/views/components/admin-header.blade.php -->
<header class="fixed top-0 left-0 right-0 z-30 bg-white shadow-sm h-16 flex items-center px-4 md:px-6"
        x-data="{ isDropdownOpen: false }">
    <div class="container mx-auto flex items-center justify-between">
        <!-- Left: Menu Toggle & Title -->
        <div class="flex items-center space-x-4">
            <button id="toggleSidebar" class="md:hidden text-gray-800 focus:outline-none" aria-label="Toggle sidebar"
                    x-on:click="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-900">Admin Dashboard</h1>
        </div>

        <!-- Right: User Dropdown -->
        <div class="relative">
            <button x-on:click="isDropdownOpen = !isDropdownOpen" id="dropdownToggle"
                    class="flex items-center space-x-2 focus:outline-none rounded-full p-1 bg-gray-100 transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="hidden sm:block font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="dropdownMenu" x-show="isDropdownOpen" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 opacity-100"
                 x-transition:leave-end="scale-95 opacity-0"
                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden"
                 x-on:click.away="isDropdownOpen = false">
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@email.com' }}</p>
                </div>
                <div class="py-1">
                    <form wire:navigate method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h3a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>