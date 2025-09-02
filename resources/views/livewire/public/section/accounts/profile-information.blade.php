<!-- resources/views/livewire/public/section/accounts/profile-information.blade.php -->
<div class="container min-h-screen bg-white mx-auto px-4 py-6 max-w-7xl flex flex-col lg:flex-row gap-6">
    <!-- Sidebar -->
    <livewire:public.section.accounts.sidebar/>

    <div class="w-full lg:w-9/12 bg-gray-50 rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Personal Information</h2>

        @if($information)
            <!-- Display Mode -->
            <div id="display-mode" class="{{ $editing || $changingPassword ? 'hidden' : '' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-600">Full Name</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $information->name }}</p>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-600">Email Address</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $information->email }}</p>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-600">Joined Date</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $information->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="mt-6 flex space-x-4">
                    {{-- <button onclick="toggleEditForm()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        Edit Profile
                    </button> --}}
                    <button onclick="togglePasswordForm()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        Change Password
                    </button>
                </div>
            </div>

            <!-- Profile Edit Form -->
            <div id="edit-mode" class="{{ $editing ? '' : 'hidden' }}">
                <form wire:submit.prevent="updateProfile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-600">Full Name</label>
                            <input 
                                type="text" 
                                wire:model="information.name" 
                                class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter your name"
                            >
                            @error('information.name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-600">Email Address</label>
                            <input 
                                type="email" 
                                wire:model="information.email" 
                                class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter your email"
                            >
                            @error('information.email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex space-x-4">
                        <button 
                            type="submit" 
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition"
                        >
                            Save Changes
                        </button>
                        <button 
                            type="button" 
                            wire:click="cancelEdit" 
                            onclick="toggleEditForm()" 
                            class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change Form (unchanged) -->
            <div id="password-mode" class="{{ $changingPassword ? '' : 'hidden' }}">
                <form wire:submit.prevent="changePassword">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-600">Current Password</label>
                            <input 
                                type="password" 
                                wire:model="current_password" 
                                class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Enter current password"
                            >
                            @error('current_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-600">New Password</label>
                            <input 
                                type="password" 
                                wire:model="new_password" 
                                class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Enter new password"
                            >
                            @error('new_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-600">Confirm New Password</label>
                            <input 
                                type="password" 
                                wire:model="new_password_confirmation" 
                                class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Confirm new password"
                            >
                            @error('new_password_confirmation') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex space-x-4">
                        <button 
                            type="submit" 
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition"
                        >
                            Save Password
                        </button>
                        <button 
                            type="button" 
                            wire:click="cancelEdit" 
                            onclick="togglePasswordForm()" 
                            class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Success/Error Message -->
            @if(session('message'))
                <p class="text-green-600 mt-4">{{ session('message') }}</p>
            @endif
        @else
            <p class="text-red-600">No user information found.</p>
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