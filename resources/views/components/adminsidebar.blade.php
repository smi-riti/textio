<!-- resources/views/components/adminsidebar.blade.php -->
<aside 
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg sidebar lg:static flex-shrink-0"
    id="sidebar" 
    :class="{ 'hidden': !isSidebarOpen, 'block': isSidebarOpen, 'lg:block': true }">

    <!-- Logo Section -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <a wire:navigate href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-primary">Textio</a>
        <button id="closeSidebar" class="lg:hidden text-dark" aria-label="Close sidebar" x-on:click="toggleSidebar()">
            <i class="bi bi-x text-2xl"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4 overflow-y-auto" style="height: calc(100vh - 130px)">
        <p class="text-xs uppercase text-gray-500 tracking-wider mb-4">E-Commerce</p>
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a wire:navigate href="{{ route('admin.dashboard') }}"
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is('admin.dashboard') ? 'menu-item-active' : '' }}">
                    <i class="bi bi-bar-chart mr-3 text-primary"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Orders -->
            <li>
                <a wire:navigate href="{{ route('admin.orderindex') }}"
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is('admin.orders.index') ? 'menu-item-active' : '' }}">
                    <i class="bi bi-receipt mr-3 text-primary"></i>
                    <span>Orders</span>
                </a>
            </li>

            <!-- Category -->
            <li>
                <a wire:navigate href="{{ route('admin.categories.index') }}"
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is('admin.categories.index') ? 'menu-item-active' : '' }}">
                    <i class="bi bi-grid mr-3 text-primary"></i>
                    <span>Category</span>
                </a>
            </li>

            <!-- Brand -->
            <li>
                <a wire:navigate href="{{ route('admin.brands') }}"
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is('admin.brands') ? 'menu-item-active' : '' }}">
                    <i class="bi bi-bookmark mr-3 text-primary"></i>
                    <span>Brand</span>
                </a>
            </li>

            <!-- Products -->
            <li>
                <a wire:navigate href="{{ route('admin.products.index') }}"
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is('admin.products.index') ? 'menu-item-active' : '' }}">
                    <i class="bi bi-box mr-3 text-primary"></i>
                    <span>Products</span>
                </a>
            </li>

            <!-- Manage Variants -->
            <li x-data="{ submenuOpen_variants: false }">
                <button
                    x-on:click="submenuOpen_variants = !submenuOpen_variants"
                    class="toggle-submenu flex items-center justify-between w-full p-3 rounded-lg hover:bg-gray-100 transition-all">
                    <div class="flex items-center">
                        <i class="bi bi-gear mr-3 text-primary"></i>
                        <span>Manage Variants</span>
                    </div>
                    <i class="bi bi-chevron-down text-xs transition-transform" :class="{ 'rotate-180': submenuOpen_variants }"></i>
                </button>
                <ul class="submenu ml-6 mt-1 space-y-1" :class="{ 'open': submenuOpen_variants }">
                    <li>
                        <a wire:navigate href="{{ route('admin.VariantName') }}"
                           class="block p-2 rounded-lg hover:bg-gray-100 transition-all text-sm {{ Route::is('admin.VariantName') ? 'menu-item-active' : '' }}">
                            Variant Name
                        </a>
                    </li>
                    <li>
                        <a wire:navigate href="{{ route('admin.VariantValues') }}"
                           class="block p-2 rounded-lg hover:bg-gray-100 transition-all text-sm {{ Route::is('admin.VariantValues') ? 'menu-item-active' : '' }}">
                            Variant Values
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Customers -->
            <li>
                <a wire:navigate href="{{ route('admin.customer') }}"
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is('admin.customers.index') ? 'menu-item-active' : '' }}">
                    <i class="bi bi-person-badge mr-3 text-primary"></i>
                    <span>Customers</span>
                </a>
            </li>

            <!-- Coupons -->
            <li>
                <a wire:navigate href="{{ route('admin.coupon') }}"
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is('admin.coupons.index') ? 'menu-item-active' : '' }}">
                    <i class="bi bi-tag mr-3 text-primary"></i>
                    <span>Coupons</span>
                </a>
            </li>

            <!-- Reviews -->
            <li>
                <a wire:navigate href="{{route('admin.review.approve')}}"
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is('admin.review.approve') ? 'menu-item-active' : '' }}">
                    <i class="bi bi-star mr-3 text-primary"></i>
                    <span>Reviews</span>
                </a>
            </li>
        </ul>

        <!-- Actions Section -->
        <ul class="pt-4 mt-4 border-t border-gray-200">
            <p class="text-xs uppercase text-gray-500 tracking-wider mb-4">Actions</p>
            <li>
                 <form action="{{ route('logout') }}" method="POST">
                        @csrf
                    <button type="submit"
                            class="w-full flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all text-left">
                        <i class="bi bi-box-arrow-right mr-3 text-red-500"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>