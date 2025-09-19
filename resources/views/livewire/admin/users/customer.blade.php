<div class="w-full p-4 md:p-6 bg-white rounded-xl shadow-md">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-gray-800 font-medium text-xl md:text-2xl">Customer Management</h1>
        <div class="w-full sm:w-auto">
            <div class="relative">
                <input type="text" placeholder="Search customers..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent shadow-sm">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="w-full bg-white overflow-x-auto rounded-lg shadow-sm border border-gray-100">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-[#171717] text-white">
                    <th class="p-3 text-left font-medium text-sm uppercase tracking-wider">S/No</th>
                    <th class="p-3 text-left font-medium text-sm uppercase tracking-wider">Email</th>
                    <th class="p-3 text-left font-medium text-sm uppercase tracking-wider">Name</th>
                    <th class="p-3 text-left font-medium text-sm uppercase tracking-wider">Join Date</th>
                    <th class="p-3 text-left font-medium text-sm uppercase tracking-wider">Time</th>
                    <th class="p-3 text-left font-medium text-sm uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $index => $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 even:bg-gray-50/30">
                        <td class="p-3 whitespace-nowrap text-sm text-gray-700">{{$index+1}}</td>
                        <td class="p-3 whitespace-nowrap text-sm text-gray-700">{{$user->email}}</td>
                        <td class="p-3 whitespace-nowrap text-sm text-gray-700">{{$user->name}}</td>
                        <td class="p-3 whitespace-nowrap text-sm text-gray-700">{{ $user->created_at->format('Y-m-d')}}</td>
                        <td class="p-3 whitespace-nowrap text-sm text-gray-700">{{ $user->created_at->format('H:i:s') }}</td>
                        <td class="p-3 whitespace-nowrap text-sm">
                            <button wire:click="delete({{ $user->id }})" class="text-red-500 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition-colors duration-200 shadow-sm hover:shadow" title="Delete" onclick="return confirm('Are you sure you want to delete this customer?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    </tr> 
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-4">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-500 text-sm mt-2">No customers found.</p>
                            </div>
                        </td>
                    </tr> 
                @endforelse
            </tbody>
        </table>
    </div>
</div>