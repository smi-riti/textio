 <div class="w-full lg:w-3/12 hidden lg:block bg-gray-100 p-6 rounded-xl shadow-lg">
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-900">Hello, {{ Auth::user()->name ?? 'User' }}</h3>
            <p class="text-sm text-gray-600 mt-1">Manage your account and orders</p>
        </div>
        <div class="mb-8">
            <h4 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">My Orders</h4>
            <a href="" class="block mt-3 text-sm text-gray-700 hover:text-purple-600 transition duration-200">View Orders</a>
        </div>
        <div class="mb-8">
            <h4 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Account Settings</h4>
            <ul class="mt-3 space-y-2">
                <li><a wire:navigate href="{{route('profile-information')}}" class="text-sm text-gray-700 hover:text-purple-600 transition duration-200">Profile Information</a></li>
                <li><a href="{{route('Manage-address')}}" class="text-sm text-gray-700 hover:text-purple-600 transition duration-200">Manage Address</a></li>              
                  <li><a wire:navigate href="{{route('wishlist.index')}}" class="text-sm text-gray-700 hover:text-purple-600 transition duration-200">My Wishlist</a></li>

            </ul>
        </div>
        <div>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg text-sm font-semibold hover:bg-red-700 transition duration-300">
                    Logout
                </button>
            </form>
        </div>
    </div>
