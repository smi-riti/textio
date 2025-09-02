<div class="container min-h-screen bg-gradient-to-br from-purple-50 to-white mx-auto px-4 py-8 max-w-7xl flex flex-col lg:flex-row gap-8">
    <!-- Sidebar -->
    <livewire:public.section.accounts.sidebar/>

    <div class="w-full lg:w-9/12 bg-white rounded-xl p-8 transition-all duration-300">
        <h2 class="text-3xl font-semiblod text-purple-800 mb-6">Your Profile</h2>
        <p class="text-gray-600 mb-6">Manage your personal details with ease. Update your information to keep it current!</p>

        @if($information)
            <!-- Display Mode -->
            <div id="display-mode" class="{{ $editing || $changingPassword ? 'hidden' : '' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label class="text-sm font-semibold text-purple-700">Full Name</label>
                        <p class="mt-1 text-lg text-gray-900 font-medium">{{ $information->name }}</p>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm font-semibold text-purple-700">Email Address</label>
                        <p class="mt-1 text-lg text-gray-900 font-medium">{{ $information->email }}</p>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm font-semibold text-purple-700">Joined Date</label>
                        <p class="mt-1 text-lg text-gray-900 font-medium">{{ $information->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                    <button onclick="toggleEditForm()" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-200 font-semibold">
                        Edit Profile
                    </button>
                    <button onclick="togglePasswordForm()" class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600 transition duration-200 font-semibold">
                        Change Password
                    </button>
                </div>
            </div>

            <!-- Profile Edit Form -->
            <div id="edit-mode" class="{{ $editing ? '' : 'hidden' }}">
                <form wire:submit.prevent="updateProfile">
                    <p class="text-gray-600 mb-4">Update your profile details below.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-purple-700">Full Name</label>
                            <input 
                                type="text" 
                                wire:model="information.name" 
                                class="mt-1 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-purple-50"
                                placeholder="Enter your name"
                            >
                            @error('information.name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-purple-700">Email Address</label>
                            <input 
                                type="email" 
                                wire:model="information.email" 
                                class="mt-1 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-purple-50"
                                placeholder="Enter your email"
                            >
                            @error('information.email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                        <button 
                            type="submit" 
                            class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-200 font-semibold"
                        >
                            Save Changes
                        </button>
                        <button 
                            type="button" 
                            wire:click="cancelEdit" 
                            onclick="toggleEditForm()" 
                            class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200 font-semibold"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change Form -->
            <div id="password-mode" class="{{ $changingPassword ? '' : 'hidden' }}">
                <form wire:submit.prevent="changePassword">
                    <p class="text-gray-600 mb-4">Secure your account with a new password.</p>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-purple-700">Current Password</label>
                            <input 
                                type="password" 
                                wire:model="current_password" 
                                class="mt-1 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-purple-50"
                                placeholder="Enter current password"
                            >
                            @error('current_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-purple-700">New Password</label>
                            <input 
                                type="password" 
                                wire:model="new_password" 
                                class="mt-1 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-purple-50"
                                placeholder="Enter new password"
                            >
                            @error('new_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-purple-700">Confirm New Password</label>
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
                            class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-200 font-semibold"
                        >
                            Save Password
                        </button>
                        <button 
                            type="button" 
                            wire:click="cancelEdit" 
                            onclick="togglePasswordForm()" 
                            class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200 font-semibold"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Success/Error Message -->
            @if(session('message'))
                <p class="text-purple-600 mt-6 font-medium">{{ session('message') }}</p>
            @endif
        @else
            <p class="text-red-600 font-medium">Oops! We couldn't find your information. Please try again later.</p>
        @endif
    </div>
</div>

<!-- Plain JavaScript for toggling forms -->
<script>
    function toggleEditForm() {
        const displayMode = document.getElementById('display-mode');
        const editMode = document.getElementById('edit-mode');
        const passwordMode = document.getElementById('password-mode');
        displayMode.classList.toggle('hidden');
        editMode.classList.toggle('hidden');
        passwordMode.classList.add('hidden');
        @this.set('editing', !@this.get('editing'));
        @this.set('changingPassword', false);
    }

    function togglePasswordForm() {
        const displayMode = document.getElementById('display-mode');
        const editMode = document.getElementById('edit-mode');
        const passwordMode = document.getElementById('password-mode');
        displayMode.classList.toggle('hidden');
        passwordMode.classList.toggle('hidden');
        editMode.classList.add('hidden');
        @this.set('changingPassword', !@this.get('changingPassword'));
        @this.set('editing', false);
    }
</script>