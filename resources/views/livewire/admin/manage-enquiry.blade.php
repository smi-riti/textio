<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Manage Enquiries</h1>
        <p class="text-gray-600">View and manage customer enquiries and messages</p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Enquiries</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $this->totalCount }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Unread</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $this->unreadCount }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.94 6.412A2 2 0 002 8.108V16a2 2 0 002 2h12a2 2 0 002-2V8.108a2 2 0 00-.94-1.696l-6-3.75a2 2 0 00-2.12 0l-6 3.75zm3.867 2.221L10 10.74l3.193-2.107A1 1 0 0114 9.5v5.5H6V9.5a1 1 0 00.807-.867z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Read</p>
                    <p class="text-3xl font-bold text-green-600">{{ $this->readCount }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.94 6.412A2 2 0 002 8.108V16a2 2 0 002 2h12a2 2 0 002-2V8.108a2 2 0 00-.94-1.696l-6-3.75a2 2 0 00-2.12 0l-6 3.75zm3.867 2.221L10 10.74l3.193-2.107A1 1 0 0114 9.5v5.5H6V9.5a1 1 0 00.807-.867z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Tabs -->
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button 
                    wire:click="setActiveTab('unread')"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ $activeTab === 'unread' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Unread ({{ $this->unreadCount }})
                </button>
                <button 
                    wire:click="setActiveTab('read')"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ $activeTab === 'read' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Read ({{ $this->readCount }})
                </button>
                <button 
                    wire:click="setActiveTab('all')"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ $activeTab === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    All ({{ $this->totalCount }})
                </button>
            </div>

            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent w-full lg:w-80"
                    placeholder="Search enquiries...">
            </div>
        </div>
    </div>

    <!-- Enquiries Table -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <button 
                                wire:click="sortBy('name')"
                                class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wide hover:text-gray-700">
                                <span>Name</span>
                                @if($sortBy === 'name')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd"/>
                                        @else
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168 13.71 7.23a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <button 
                                wire:click="sortBy('email')"
                                class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wide hover:text-gray-700">
                                <span>Email</span>
                                @if($sortBy === 'email')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd"/>
                                        @else
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168 13.71 7.23a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <button 
                                wire:click="sortBy('subject')"
                                class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wide hover:text-gray-700">
                                <span>Subject</span>
                                @if($sortBy === 'subject')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd"/>
                                        @else
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168 13.71 7.23a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <button 
                                wire:click="sortBy('created_at')"
                                class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wide hover:text-gray-700">
                                <span>Date</span>
                                @if($sortBy === 'created_at')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd"/>
                                        @else
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168 13.71 7.23a.75.75 0 111.08 1.04l-4.25-4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($this->enquiries as $enquiry)
                        <tr class="hover:bg-gray-50 {{ !$enquiry->is_read ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if(!$enquiry->is_read)
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $enquiry->name }}</div>
                                        @if($enquiry->phone)
                                            <div class="text-sm text-gray-500">{{ $enquiry->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $enquiry->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($enquiry->subject, 30) }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($enquiry->message, 50) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($enquiry->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Read
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Unread
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $enquiry->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $enquiry->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end space-x-1">
                                    <button 
                                        wire:click="viewEnquiry({{ $enquiry->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 rounded-lg hover:bg-blue-100 transition-colors"
                                        title="View">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    
                                    @if($enquiry->is_read)
                                        <button 
                                            wire:click="markAsUnread({{ $enquiry->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 text-purple-600 hover:text-purple-800 rounded-lg hover:bg-purple-100 transition-colors"
                                            title="Mark as Unread">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button 
                                            wire:click="markAsRead({{ $enquiry->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-800 rounded-lg hover:bg-green-100 transition-colors"
                                            title="Mark as Read">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.94 6.412A2 2 0 002 8.108V16a2 2 0 002 2h12a2 2 0 002-2V8.108a2 2 0 00-.94-1.696l-6-3.75a2 2 0 00-2.12 0l-6 3.75zm3.867 2.221L10 10.74l3.193-2.107A1 1 0 0114 9.5v5.5H6V9.5a1 1 0 00.807-.867z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    <button 
                                        wire:click="deleteEnquiry({{ $enquiry->id }})"
                                        onclick="return confirm('Are you sure you want to delete this enquiry?')"
                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 rounded-lg hover:bg-red-100 transition-colors"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V9a4 4 0 01-4 4H9a4 4 0 01-4-4V5"/>
                                    </svg>
                                    <p class="text-lg font-medium">No enquiries found</p>
                                    <p class="text-sm">
                                        @if($activeTab === 'unread')
                                            There are no unread enquiries at the moment.
                                        @elseif($activeTab === 'read')
                                            There are no read enquiries at the moment.
                                        @else
                                            No enquiries have been submitted yet.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($this->enquiries->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $this->enquiries->links() }}
            </div>
        @endif
    </div>

    <!-- Modal for viewing enquiry details -->
    @if($showModal && $selectedEnquiry)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" wire:click="closeModal">
            <div class="bg-white rounded-2xl max-w-2xl w-full h-[600px] flex flex-col" wire:click.stop>
                <!-- Modal Header - Fixed -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-xl font-bold text-gray-800">Enquiry Details</h3>
                    <button 
                        wire:click="closeModal"
                        class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content - Flexible -->
                <div class="flex-1 p-6 min-h-0">
                    <div class="h-full flex flex-col space-y-4">
                        <!-- Status and Date -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg flex-shrink-0">
                            <div class="flex items-center space-x-2">
                                @if($selectedEnquiry->is_read)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Read
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Unread
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $selectedEnquiry->created_at->format('M d, Y \a\t H:i') }}
                            </div>
                        </div>

                        <!-- Contact Information Grid -->
                        <div class="grid grid-cols-2 gap-3 flex-shrink-0">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Name</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded">{{ $selectedEnquiry->name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded break-all">{{ $selectedEnquiry->email }}</p>
                            </div>
                        </div>

                        @if($selectedEnquiry->phone)
                        <div class="flex-shrink-0">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Phone</label>
                            <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded">{{ $selectedEnquiry->phone }}</p>
                        </div>
                        @endif

                        <div class="flex-shrink-0">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Subject</label>
                            <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded">{{ $selectedEnquiry->subject }}</p>
                        </div>

                        <div class="flex-1 min-h-0">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Message</label>
                            <div class="h-full bg-gray-50 p-3 rounded text-sm text-gray-900 break-words" style="word-wrap: break-word; overflow-wrap: break-word;">{{ $selectedEnquiry->message }}</div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer - Fixed -->
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 flex-shrink-0">
                    @if($selectedEnquiry->is_read)
                        <button 
                            wire:click="markAsUnread({{ $selectedEnquiry->id }})"
                            class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Mark as Unread
                        </button>
                    @else
                        <button 
                            wire:click="markAsRead({{ $selectedEnquiry->id }})"
                            class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.94 6.412A2 2 0 002 8.108V16a2 2 0 002 2h12a2 2 0 002-2V8.108a2 2 0 00-.94-1.696l-6-3.75a2 2 0 00-2.12 0l-6 3.75zm3.867 2.221L10 10.74l3.193-2.107A1 1 0 0114 9.5v5.5H6V9.5a1 1 0 00.807-.867z" clip-rule="evenodd"/>
                            </svg>
                            Mark as Read
                        </button>
                    @endif

                    <button 
                        wire:click="closeModal"
                        class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>