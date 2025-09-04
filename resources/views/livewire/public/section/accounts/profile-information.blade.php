<div class="container min-h-screen bg-gradient-to-br from-purple-50 to-white mx-auto px-4 py-8 max-w-7xl flex flex-col lg:flex-row gap-8">
    <!-- Sidebar -->
    <livewire:public.section.accounts.sidebar/>

   <div class="w-full lg:w-9/12 bg-white rounded-2xl p-8 transition-all duration-300">
    @if($information)
        <!-- Display Mode -->
        <div id="display-mode" class="{{ $editing || $changingPassword ? 'hidden' : '' }}">
            <div class="flex items-center justify-center mb-6">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                    <span class="text-2xl text-purple-800">{{ substr($information->name, 0, 1) }}</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm text-purple-700">Full Name</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $information->name }}</p>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-purple-700">Email Address</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $information->email }}</p>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-purple-700">Joined Date</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $information->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                <button onclick="togglePasswordForm()" class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600 transition duration-200">
                    Change Password
                </button>
            </div>
        </div>

        <!-- Password Change Form -->
        <div id="password-mode" class="{{ $changingPassword ? '' : 'hidden' }}">
            <form wire:submit.prevent="changePassword">
                <p class="text-gray-600 mb-4">Secure your account with a new password.</p>
                <div class="grid grid-cols-1 gap-6">
                    <div class="flex flex-col">
                        <label class="text-sm text-purple-700">Current Password</label>
                        <input 
                            type="password" 
                            wire:model="current_password" 
                            class="mt-1 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-purple-50"
                            placeholder="Enter current password"
                        >
                        @error('current_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-purple-700">New Password</label>
                        <input 
                            type="password" 
                            wire:model="new_password" 
                            class="mt-1 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-purple-50"
                            placeholder="Enter new password"
                        >
                        @error('new_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-purple-700">Confirm New Password</label>
                        <input 
                            type="password" 
                            wire:model="new_password_confirmation" 
                            class="mt-1 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-purple-50"
                            placeholder="Confirm new password"
                        >
                        @error('new_password_confirmation') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                    <button 
                        type="submit" 
                        class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-200"
                    >
                        Save Password
                    </button>
                    <button 
                        type="button" 
                        wire:click="cancelEdit" 
                        onclick="togglePasswordForm()" 
                        class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Success/Error Message -->
        @if(session('message'))
            <p class="text-purple-600 mt-6">{{ session('message') }}</p>
        @endif
    @else
        <p class="text-red-600">Oops! We couldn't find your information. Please try again later.</p>
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