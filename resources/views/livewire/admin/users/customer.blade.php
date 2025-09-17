<div class="flex flex-1 flex-col p-4 sm:p-6">
    <div class="bg-white dark:bg-[#171717] rounded overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-[#171717] text-white">
                <tr>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-widest sm:px-6">S/No</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-widest sm:px-6">Email</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-widest sm:px-6">Name</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-widest sm:px-6">Join Date</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-widest sm:px-6">Time</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-widest sm:px-6">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-[#171717] divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Sample Data for Design Preview -->
                @forelse ($users as $index => $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white sm:px-6">{{$index+1}}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white sm:px-6">{{$user->email}}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white sm:px-6">{{$user->name}}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white sm:px-6">{{ $user->created_at->format('Y-m-d')}}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white sm:px-6">{{ $user->created_at->format('H:i:s') }}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm sm:px-6">
                        <div class="flex space-x-3">
                           <button wire:click="delete({{ $user->id }})" class="text-red-500 hover:text-red-600 transition-colors duration-150" title="Delete" onclick="return confirm('Are you sure you want to delete this customer?')">
                                    <i class="bi bi-trash text-lg"></i>
                                </button>
                        </div>
                    </td>
                </tr> 
                @empty
                    <tr class="hidden">
                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400 sm:px-6">No customers found.</td>
                </tr> 
                @endforelse
               
               
               
            </tbody>
        </table>
    </div>
</div>