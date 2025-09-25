<div class="container min-h-screen bg-gradient-to-br from-gray-50 to-white mx-auto px-4 py-8 max-w-7xl flex flex-col lg:flex-row gap-8">
    <!-- Sidebar -->
    <livewire:public.section.accounts.sidebar/>

    <div class="w-full lg:w-9/12 bg-white rounded-2xl  border border-gray-100 p-8 transition-all duration-300">
        @if($information)
            <!-- Display Mode -->
            <div id="display-mode" class="{{ $editing || $changingPassword ? 'hidden' : '' }}">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-r from-[#8f4da7] to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 ">
                            <span class="text-3xl font-semiblod text-white">{{ substr($information->name, 0, 1) }}</span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <h2 class="text-2xl font-semiblod text-gray-900">{{ $information->name }}</h2>
                    <p class="text-gray-600 mt-1">{{ $information->email }}</p>
                    <div class="inline-flex items-center mt-2 px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Member since {{ $information->created_at->format('M Y') }}
                    </div>
                </div>

                <!-- Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-[#8f4da7]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Personal Information</h3>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Full Name</label>
                                <p class="text-gray-900 font-medium">{{ $information->name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email Address</label>
                                <p class="text-gray-900 font-medium">{{ $information->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Account Details</h3>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Joined Date</label>
                                <p class="text-gray-900 font-medium">{{ $information->created_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Account Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="togglePasswordForm()" 
                                class="flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#8f4da7] to-purple-600 text-white font-medium rounded-lg hover:from-[#7e3d96] hover:to-purple-700 transition-all duration-200 transform hover:scale-105 ">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Change Password
                        </button>
                        
                       
                    </div>
                </div>
            </div>

            <!-- Password Change Form -->
            <div id="password-mode" class="{{ $changingPassword ? '' : 'hidden' }}">
                <div class="max-w-md mx-auto">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-[#8f4da7] to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semiblod text-gray-900">Change Password</h2>
                        <p class="text-gray-600 mt-2">Secure your account with a new password</p>
                    </div>

                    <form wire:submit.prevent="changePassword" class="space-y-6">
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Current Password
                                </label>
                                <input 
                                    type="password" 
                                    wire:model="current_password" 
                                    class="p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-all duration-200 bg-white"
                                    placeholder="Enter your current password"
                                >
                                @error('current_password') 
                                    <span class="text-red-600 text-sm mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </span> 
                                @enderror
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    New Password
                                </label>
                                <input 
                                    type="password" 
                                    wire:model="new_password" 
                                    class="p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-all duration-200 bg-white"
                                    placeholder="Enter your new password"
                                >
                                @error('new_password') 
                                    <span class="text-red-600 text-sm mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </span> 
                                @enderror
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Confirm New Password
                                </label>
                                <input 
                                    type="password" 
                                    wire:model="new_password_confirmation" 
                                    class="p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:border-transparent transition-all duration-200 bg-white"
                                    placeholder="Confirm your new password"
                                >
                                @error('new_password_confirmation') 
                                    <span class="text-red-600 text-sm mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </span> 
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <button 
                                type="submit" 
                                class="flex-1 bg-gradient-to-r from-[#8f4da7] to-purple-600 text-white font-medium py-4 rounded-xl hover:from-[#7e3d96] hover:to-purple-700 transition-all duration-200 transform hover:scale-105 "
                            >
                                Update Password
                            </button>
                            <button 
                                type="button" 
                                wire:click="cancelEdit" 
                                onclick="togglePasswordForm()" 
                                class="flex-1 bg-gray-100 text-gray-700 font-medium py-4 rounded-xl hover:bg-gray-200 transition-all duration-200 border border-gray-300"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success/Error Message -->
            @if(session('message'))
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-800 font-medium">{{ session('message') }}</span>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Information Not Available</h3>
                <p class="text-gray-600">We couldn't find your account information. Please try again later.</p>
            </div>
        @endif
    </div>
</div>

<!-- Plain JavaScript for toggling forms -->
<script>
    function togglePasswordForm() {
        const displayMode = document.getElementById('display-mode');
        const passwordMode = document.getElementById('password-mode');
        displayMode.classList.toggle('hidden');
        passwordMode.classList.toggle('hidden');
        @this.set('changingPassword', !@this.get('changingPassword'));
        @this.set('editing', false);
    }
</script>