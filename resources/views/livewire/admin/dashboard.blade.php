<div class="w-full max-w-7xl mx-auto  sm:px-6 lg:px-4 ">

    {{-- header  --}}
    <div class="mb-8">
        <h1 class="text-2xl text-[#171717]">Welcome, {{Auth::user()->name}}</h1>
        <p class="text-[#8f4da7]">Here's what's happening with your store today</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Customers Card -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 card-customers">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-[#8f4da7] bg-opacity-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f4da7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-green-500 text-xs font-medium bg-green-100 px-2 py-1 rounded-full">
                    +5.27%
                </span>
            </div>
            <p class="text-gray-600 text-sm uppercase tracking-wider">Customers</p>
            <p class="text-2xl text-[#171717] mt-1">{{ $customers }}</p>
            <p class="text-gray-500 text-xs mt-2">Since last month</p>
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 card-orders">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-[#8f4da7] bg-opacity-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f4da7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-red-500 text-xs font-medium bg-red-100 px-2 py-1 rounded-full">
                    -1.08%
                </span>
            </div>
            <p class="text-gray-600 text-sm uppercase tracking-wider">Orders</p>
            <p class="text-2xl text-[#171717] mt-1">{{ number_format($orders) }}</p>
            <p class="text-gray-500 text-xs mt-2">Since last month</p>
        </div>

        <!-- Revenue Card -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 card-revenue">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-[#8f4da7] bg-opacity-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f4da7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-red-500 text-xs font-medium bg-red-100 px-2 py-1 rounded-full">
                    -7.00%
                </span>
            </div>
            <p class="text-gray-600 text-sm uppercase tracking-wider">Revenue</p>
            <p class="text-2xl text-[#171717] mt-1">₹{{ number_format($revenue, 2) }}</p>
            <p class="text-gray-500 text-xs mt-2">Since last month</p>
        </div>

        <!-- Growth Card -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 card-growth">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-[#8f4da7] bg-opacity-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f4da7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <span class="text-green-500 text-xs font-medium bg-green-100 px-2 py-1 rounded-full">
                    +4.87%
                </span>
            </div>
            <p class="text-gray-600 text-sm uppercase tracking-wider">Average Revenue</p>
            <p class="text-2xl text-[#171717] mt-1">₹{{ number_format($averageRevenue, 2) }}</p>
            <p class="text-gray-500 text-xs mt-2">Since last month</p>
        </div>
    </div>

    {{-- Current Orders & Out of Stock Sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Current Orders Section -->
        <div class="bg-white rounded-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg text-[#171717]">Current Orders</h2>
                <a wire:navigate href="{{route('admin.orderindex')}}" class="text-sm text-[#8f4da7]">View all</a>
            </div>
            <div class="space-y-4">
                <!-- Order Item -->
                @foreach ($CurrentOrder as $order)
                     <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <p class="text-[#171717]">#{{$order->order->order_number}}</p>
                        <p class="text-gray-500 text-sm">{{$order->order->user->name}} • {{$order->quantity}} items</p>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">{{$order->order->status}}</span>
                </div>
                @endforeach
               
                
                
                
               
            </div>
        </div>

        <!-- Out of Stock Section -->
        <div class="bg-white rounded-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg text-[#171717]">Out of Stock</h2>
            </div>
            <div class="space-y-4">
                <!-- Product Item -->
               @forelse ($outOFstock as $stock)
                   <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div class="flex items-center">
                        
                        <div>
                            <p class="text-[#171717] truncate w-48">{{$stock->product->name}}</p>
                            <p class="text-gray-500 text-sm">{{$stock->product->category->title}}</p>
                        </div>
                    </div>
                    <span class="text-red-500 text-sm">{{$stock->stock}} in stock</span>
                </div>
               @empty
                   
               @endforelse
              
                
                
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="bg-white rounded-lg border border-gray-100 p-6">
        <h2 class="text-lg text-[#171717] mb-6">Quick Links</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            <a wire:navigate href="{{ route('admin.products.create') }}" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-100 hover:bg-[#8f4da7] hover:bg-opacity-10 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-[#8f4da7] bg-opacity-10 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f4da7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <span class="text-sm text-[#171717]">Add Product</span>
            </a>
            
            <a wire:navigate href="{{route('admin.customer')}}" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-100 hover:bg-[#8f4da7] hover:bg-opacity-10 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-[#8f4da7] bg-opacity-10 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f4da7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-sm text-[#171717]">Customers</span>
            </a>
            
            <a wire:navigate href="{{route('admin.orderindex')}}" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-100 hover:bg-[#8f4da7] hover:bg-opacity-10 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-[#8f4da7] bg-opacity-10 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f4da7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-sm text-[#171717]">Orders</span>
            </a>
            
            <a wire:navigate href="{{route('admin.coupon')}}" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-100 hover:bg-[#8f4da7] hover:bg-opacity-10 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-[#8f4da7] bg-opacity-10 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f4da7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="text-sm text-[#171717]">Coupons</span>
            </a>
        </div>
    </div>

</div>