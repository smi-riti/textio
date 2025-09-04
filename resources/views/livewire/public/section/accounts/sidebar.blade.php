<div class="w-full lg:w-3/12 hidden  lg:block bg-gradient-to-b from-gray-50 to-gray-100 p-6 rounded-xl">
    <!-- Logo Section -->
    <div class="flex items-center mb-8">
        <i class="fas fa-user text-xl text-purple-600 mr-2"></i>
        <h3 class="text-xl font-semibold text-gray-900">Hello, {{ Auth::user()->name ?? 'User' }}</h3>
    </div>
    <p class="text-sm text-gray-600 mb-8">Manage your account and orders</p>

    <!-- My Orders Section -->
    <div class="mb-8">
        <h4 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">My Orders</h4>
        <a href="{{ route('myOrders') }}"
            class="block mt-3 text-sm text-gray-700 hover:text-purple-600 transition duration-200 ease-in-out">View
            Orders</a>
    </div>

    <!-- Account Settings Section -->
    <div class="mb-8">
        <h4 class="text-stransition font-semibold text-gray-800 uppercase tracking-wide">Account Settings</h4>
        <ul class="mt-3 space-y-2">
            <li><a wire:navigate href="{{ route('profile-information') }}"
                    class="text-sm text-gray-700 hover:text-purple-600 transition duration-200 ease-in-out flex items-center">
                    <i class="fas fa-user text-xs text-gray-500 mr-2"></i> Profile Information
                </a></li>
            <li><a href="{{ route('Manage-address') }}"
                    class="text-sm text-gray-700 hover:text-purple tool-600 transition duration-200 ease-in-out flex items-center">
                    <i class="fas fa-map-marker-alt text-xs text-gray-500 mr-2"></i> Manage Address
                </a></li>
            <li><a wire:navigate href="{{ route('wishlist.index') }}"
                    class="text-sm text-gray-700 hover:text-purple-600 transition duration-200 ease-in-out flex items-center">
                    <i class="fas fa-heart text-xs text-gray-500 mr-2"></i> My Wishlist
                </a></li>
        </ul>
    </div>

    <!-- Logout Button -->
    <div>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit"
                class="w-full border border-purple-200 text-purple-600   text-gray-700 py-2 rounded text-sm font-medium  transition duration-300 ease-in-out">
                <i class="fas mt-1  fa-sign-in-alt text-xm"></i>
                Logout
            </button>
        </form>
    </div>
</div>
