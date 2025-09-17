<div>
    <div class="menu">
        <div class="menu-header">
            <a href="index.html" class="menu-header-logo">
                <h1>Textio</h1>
            </a>
            <a href="index.html" class="btn btn-sm menu-close-btn">
                <i class="bi bi-x"></i>
            </a>
        </div>
        <div class="menu-body">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown">
                    <div class="avatar me-3">
                        <img src="{{ asset('assets/images/user/man_avatar3.jpg') }}" class="rounded-circle"
                            alt="image">
                    </div>
                    <div>
                        <div class="fw-bold">Admin Dashboard</div>
                        <small class="text-muted">Owner</small>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="#" class="dropdown-item d-flex align-items-center">
                        <i class="bi bi-person dropdown-item-icon"></i> Profile
                    </a>
                    <a href="#" class="dropdown-item d-flex align-items-center">
                        <i class="bi bi-envelope dropdown-item-icon"></i> Inbox
                    </a>
                    <a href="#" class="dropdown-item d-flex align-items-center" data-sidebar-target="#settings">
                        <i class="bi bi-gear dropdown-item-icon"></i> Settings
                    </a>
                    <a href="login.html" class="dropdown-item d-flex align-items-center text-danger" target="_blank">
                        <i class="bi bi-box-arrow-right dropdown-item-icon"></i> Logout
                    </a>
                </div>
            </div>
            <ul>
                <li class="menu-divider">E-Commerce</li>
                <li>
                    <a wire:navigate class="active" href="{{route('admin.dashboard')}}">
                        <span class="nav-link-icon">
                            <i class="bi bi-bar-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <span>Orders</span>
                    </a>
                    <ul>
                        <li>
                            <a href="orders.html">List</a>
                        </li>
                        <li>
                            <a href="order-detail.html">Detail</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a wire:navigate href="{{ route('admin.categories.index') }}">
                        <span class="nav-link-icon">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <span>Category</span>
                    </a>
                </li>
                <li>
                    <a wire:navigate href="{{ route('admin.brands') }}">
                        <span class="nav-link-icon">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <span>Brand</span>
                    </a>
                </li>

                <li>
                    <a wire:navigate href="{{ route('admin.products.index') }}">
                        <span class="nav-link-icon">
                            <i class="bi bi-truck"></i>
                        </span>
                        <span>Products</span>
                    </a>

                </li>
                <li>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <span>Manage Variants</span>
                    </a>
                    <ul>
                         <li>
                            <a href="{{route('admin.VariantName')}}">Variant Name</a>
                        </li>
                        <li>
                            <a href="{{route('admin.VariantValues')}}">Variant Values</a>
                        </li>
                        
                        
                    </ul>
                </li>

                <li>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i class="bi bi-wallet2"></i>
                        </span>
                        <span>Buyer</span>
                    </a>
                    <ul>
                        <li>
                            <a href="buyer-dashboard.html">Dashboard</a>
                        </li>
                        <li>
                            <a href="buyer-orders.html">Orders</a>
                        </li>
                        <li>
                            <a href="buyer-addresses.html">Addresses</a>
                        </li>
                        <li>
                            <a href="buyer-wishlist.html">Wishlist</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a wire:navigate href="{{route('admin.customer')}}">
                        <span class="nav-link-icon">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        <span>Customers</span>
                    </a>
                </li>
                <li>
                    <a wire:navigate href="{{ route('admin.coupon') }}">
                        <span class="nav-link-icon">
                            <i class="bi bi-tag"></i>
                        </span>
                        <span>Coupons</span>
                    </a>
                </li>

                <li class="menu-divider">Pages</li>
                <li>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i class="bi bi-person"></i>
                        </span>
                        <span>Profile</span>
                    </a>

                </li>
                {{-- <li>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <span>Users</span>
                    </a>
                    
                </li> --}}



            </ul>
        </div>
    </div>
</div>
