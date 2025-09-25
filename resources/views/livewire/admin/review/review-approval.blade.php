<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/30 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 text-center sm:text-left">
                <h2 class="text-4xl font-bold text-gray-900 mb-2 animate-fade-in-down">
                    Pending Reviews
                </h2>
                <p class="text-gray-600">Manage and approve customer reviews before they go live</p>
                
                <!-- Stats Cards -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $PendingReviews->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Approved Today</p>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-purple-100 text-purple-600 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Unique Users</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $PendingReviews->unique('user_id')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('message'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-xl shadow-lg border border-green-200 transition-all duration-300 ease-in-out transform hover:scale-[1.01] flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('message') }}
                </div>
            @endif

            @if ($PendingReviews->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="max-w-md mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl font-medium text-gray-700 mb-2">No pending reviews</h3>
                        <p class="text-gray-500">All reviews have been processed. Check back later for new submissions.</p>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Review Queue</h3>
                            <div class="mt-2 sm:mt-0 flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Sort by:</span>
                                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option>Newest First</option>
                                    <option>Oldest First</option>
                                    <option>Highest Rating</option>
                                    <option>Lowest Rating</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User & Review</th>
                                    <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rating</th>
                                    <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="p-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($PendingReviews as $review)
                                    <tr class="hover:bg-gray-50/80 transition-colors duration-150 group">
                                        <td class="p-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                                        {{ substr($review->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center space-x-2 mb-1">
                                                        <span class="font-medium text-gray-900 truncate">{{ $review->user->name }}</span>
                                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">User</span>
                                                    </div>
                                                    <p class="text-gray-700 text-sm line-clamp-2">{{ $review->review }}</p>
                                                    <button class="mt-1 text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">Read full review</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center">
                                                <span class="text-yellow-500 mr-1">★</span>
                                                <span class="font-medium text-gray-900">{{ $review->rating }}</span>
                                                <span class="text-gray-400 ml-1">/5</span>
                                            </div>
                                            <div class="flex mt-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </td>
                                        <td class="p-4 text-sm text-gray-600">
                                            {{ $review->created_at->format('M j, Y') }}<br>
                                            <span class="text-gray-400">{{ $review->created_at->format('g:i A') }}</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex space-x-2">
                                                <button wire:click="approveReview({{ $review->id }})"
                                                        class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Approve
                                                </button>
                                                <button wire:click="rejectReview({{ $review->id }})"
                                                        class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    Reject
                                                </button>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination (if needed) -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            {{-- Showing <span class="font-medium">{{ $PendingReviews->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $PendingReviews->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $PendingReviews->total() }}</span> results --}}
                        </div>
                        {{-- <div class="flex space-x-2">
                            <!-- Previous Page Link -->
                            @if ($PendingReviews->onFirstPage())
                                <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $PendingReviews->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">Previous</a>
                            @endif
                            
                            <!-- Next Page Link -->
                            @if ($PendingReviews->hasMorePages())
                                <a href="{{ $PendingReviews->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">Next</a>
                            @else
                                <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
                            @endif
                        </div> --}}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.5s ease-out;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .group:hover .group-hover\:text-blue-600 {
            color: #2563eb;
        }
    </style>
</div>