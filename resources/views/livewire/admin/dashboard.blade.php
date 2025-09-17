<div>
 
  
 <div class="w-full max-w-6xl mx-auto p-4">
       
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Customers Card -->
            <div class="card bg-white shadow-md rounded-lg p-6 text-center card-customers">
                
                <p class="text-gray-600 font-semibold text-sm uppercase tracking-wider">Customers</p>
                <p class="text-3xl font-semiblod text-gray-800 mt-2">{{$customers}}</p>
                <div class="mt-4 flex items-center justify-center">
                    <span class="text-green-500 text-sm font-medium bg-green-100 px-2 py-1 rounded-full">
                        <i class="fas fa-arrow-up mr-1"></i>5.27%
                    </span>
                    <span class="text-gray-500 text-xs ml-2">Since last month</span>
                </div>
            </div>
            
            <!-- Orders Card -->
            <div class="card bg-white shadow-md rounded-lg p-6 text-center card-orders">
               
                <p class="text-gray-600 font-semibold text-sm uppercase tracking-wider">Orders</p>
            <p class="text-3xl font-semibold text-gray-800">{{ number_format($orders) }}</p>
                <div class="mt-4 flex items-center justify-center">
                    <span class="text-red-500 text-sm font-medium bg-red-100 px-2 py-1 rounded-full">
                        <i class="fas fa-arrow-down mr-1"></i>1.08%
                    </span>
                    <span class="text-gray-500 text-xs ml-2">Since last month</span>
                </div>
            </div>
            
            <!-- Revenue Card -->
            <div class="card bg-white shadow-md rounded-lg p-6 text-center card-revenue">
               
                <p class="text-gray-600 font-semibold text-sm uppercase tracking-wider">Revenue</p>
            <p class="text-3xl font-semibold text-gray-800">₹{{ number_format($revenue, 2) }}</p>
                <div class="mt-4 flex items-center justify-center">
                    <span class="text-red-500 text-sm font-medium bg-red-100 px-2 py-1 rounded-full">
                        <i class="fas fa-arrow-down mr-1"></i>7.00%
                    </span>
                    <span class="text-gray-500 text-xs ml-2">Since last month</span>
                </div>
            </div>
            
            <!-- Growth Card -->
            <div class="card bg-white shadow-md rounded-lg p-6 text-center card-growth">
               
                <p class="text-gray-600 font-semibold text-sm uppercase tracking-wider">Avrage Ravenue</p>
            <p class="text-3xl font-semibold  text-gray-800">₹{{ number_format($averageRevenue, 2) }}</p>
                <div class="mt-4 flex items-center justify-center">
                    <span class="text-green-500 text-sm font-medium bg-green-100 px-2 py-1 rounded-full">
                        <i class="fas fa-arrow-up mr-1"></i>4.87%
                    </span>
                    <span class="text-gray-500 text-xs ml-2">Since last month</span>
                </div>
            </div>
        </div>
        
       
    </div>
    
   
   

</div>