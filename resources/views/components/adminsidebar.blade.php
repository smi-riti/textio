<!-- resources/views/components/adminsidebar.blade.php -->
<aside 
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg sidebar lg:static flex-shrink-0"
     id="sidebar" :class="{ 'hidden': !isSidebarOpen, 'block': isSidebarOpen, 'lg:block': true }">

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
            @foreach ([['route' => 'admin.dashboard', 'icon' => 'bi-bar-chart', 'label' => 'Dashboard'], ['route' => 'admin.orderindex', 'icon' => 'bi-receipt', 'label' => 'Orders'], ['route' => 'admin.categories.index', 'icon' => 'bi-receipt', 'label' => 'Category'], ['route' => 'admin.brands', 'icon' => 'bi-receipt', 'label' => 'Brand'], ['route' => 'admin.products.index', 'icon' => 'bi-truck', 'label' => 'Products']] as $item)
                <li>
                    <a wire:navigate href="{{ route($item['route']) }}"
                       class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all {{ Route::is($item['route']) ? 'menu-item-active' : '' }}">
                        <i class="bi {{ $item['icon'] }} mr-3 text-primary"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach

            <!-- Manage Variants -->
            <li x-data="{ submenuOpen_variants: false }">
                <button
                    x-on:click="submenuOpen_variants = !submenuOpen_variants"
                    class="toggle-submenu flex items-center justify-between w-full p-3 rounded-lg hover:bg-gray-100 transition-all">
                    <div class="flex items-center">
                        <i class="bi bi-receipt mr-3 text-primary"></i>
                        <span>Manage Variants</span>
                    </div>
                    <i class="bi bi-chevron-down text-xs transition-transform" :class="{ 'rotate-180': submenuOpen_variants }"></i>
                </button>
                <ul class="submenu ml-6 mt-1 space-y-1" :class="{ 'open': submenuOpen_variants }">
                    @foreach ([['route' => 'admin.VariantName', 'label' => 'Variant Name'], ['route' => 'admin.VariantValues', 'label' => 'Variant Values']] as $subitem)
                        <li>
                            <a wire:navigate href="{{ route($subitem['route']) }}"
                               class="block p-2 rounded-lg hover:bg-gray-100 transition-all text-sm">
                                {{ $subitem['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>

            @foreach ([['route' => 'admin.customer', 'icon' => 'bi-person-badge', 'label' => 'Customers'], ['route' => 'admin.coupon', 'icon' => 'bi-tag', 'label' => 'Coupons']] as $item)
                <li>
                    <a wire:navigate href="{{ route($item['route']) }}"
                       class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all">
                        <i class="bi {{ $item['icon'] }} mr-3 text-primary"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach

            <li class="pt-4 mt-4 border-t border-gray-200">
                <p class="text-xs uppercase text-gray-500 tracking-wider mb-4">Pages</p>
            </li>
            <li>
                <form wire:navigate method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center p-3 rounded-lg hover:bg-gray-100 transition-all text-left">
                        <i class="bi bi-box-arrow-right mr-3 text-red-500"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>