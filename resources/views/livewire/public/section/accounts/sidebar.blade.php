<div class="w-full lg:w-3/12 hidden lg:block bg-white border border-gray-200 p-6 rounded-xl">
    <style>
    /* Smooth transitions for hover effects */
    .transition {
        transition-property: color, background-color, border-color, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }

    /* Ensure consistent icon sizing */
    .fa-xs {
        font-size: 0.75em;
    }

    /* Custom color variables */
    .text-\[\#171717\] {
        color: #171717;
    }

    .text-\[\#8f4da7\] {
        color: #8f4da7;
    }

    .bg-\[\#8f4da7\] {
        background-color: #8f4da7;
    }

    .hover\:text-\[\#8f4da7\]:hover {
        color: #8f4da7;
    }

    .hover\:border-\[\#8f4da7\]:hover {
        border-color: #8f4da7;
    }
</style>
    <!-- User Welcome Section -->
    <div class="mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 rounded-full bg-[#8f4da7] flex items-center justify-center mr-3">
                <span class="text-white font-medium text-lg">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
            </div>
            <div>
                <h3 class="text-lg font-medium text-[#171717]">Hello, {{ Auth::user()->name ?? 'User' }}</h3>
                <p class="text-sm text-gray-500">Welcome back</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mt-2">Manage your account and orders</p>
    </div>

    <!-- My Orders Section -->
    <div class="mb-6">
        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">My Orders</h4>
        <a wire:navigate href="{{ route('myOrders') }}"
            class="flex items-center text-sm text-[#171717] hover:text-[#8f4da7] transition duration-200 ease-in-out py-2 px-3 rounded-lg hover:bg-gray-50">
            <i class="fas fa-shopping-bag text-xs text-gray-400 mr-3 w-4 text-center"></i>
            View Orders
        </a>
    </div>

    <!-- Account Settings Section -->
    <div class="mb-6">
        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Account Settings</h4>
        <ul class="space-y-1">
            <li>
                <a wire:navigate href="{{ route('profile-information') }}"
                    class="flex items-center text-sm text-[#171717] hover:text-[#8f4da7] transition duration-200 ease-in-out py-2 px-3 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-user text-xs text-gray-400 mr-3 w-4 text-center"></i>
                    Profile Information
                </a>
            </li>
            <li>
                <a wire:navigate href="{{ route('Manage-address') }}"
                    class="flex items-center text-sm text-[#171717] hover:text-[#8f4da7] transition duration-200 ease-in-out py-2 px-3 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-map-marker-alt text-xs text-gray-400 mr-3 w-4 text-center"></i>
                    Manage Address
                </a>
            </li>
            <li>
                <a wire:navigate href="{{ route('wishlist.index') }}"
                    class="flex items-center text-sm text-[#171717] hover:text-[#8f4da7] transition duration-200 ease-in-out py-2 px-3 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-heart text-xs text-gray-400 mr-3 w-4 text-center"></i>
                    My Wishlist
                </a>
            </li>
        </ul>
    </div>

    <!-- Additional Links Section -->
    <div class="mb-6">
        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Support</h4>
        <ul class="space-y-1">
           
            <li>
                <a href="{{route('contact')}}"
                    class="flex items-center text-sm text-[#171717] hover:text-[#8f4da7] transition duration-200 ease-in-out py-2 px-3 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-headset text-xs text-gray-400 mr-3 w-4 text-center"></i>
                    Contact Support
                </a>
            </li>
        </ul>
    </div>

    <!-- Logout Button -->
    <div class="pt-4 border-t border-gray-100">
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center text-sm text-gray-600 hover:text-[#8f4da7] transition duration-300 ease-in-out py-3 px-3 rounded-lg border border-gray-300 hover:border-[#8f4da7]">
                <i class="fas fa-sign-out-alt text-xs mr-2 w-4 text-center"></i>
                Logout
            </button>
        </form>
    </div>

    <!-- Current Page Indicator (Optional) -->
    <div class="mt-6 pt-4 border-t border-gray-100">
        <div class="text-xs text-gray-400">
            <span class="font-medium text-[#8f4da7]">Current:</span>
            <span class="ml-1">Profile Information</span>
        </div>
    </div>
</div>

