<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-4xl w-full mx-4">
        <div class="bg-white shadow-lg rounded-lg flex flex-col lg:flex-row">
            <!-- Left Column: Form -->
            <div class="w-full lg:w-1/2 p-6">
                <div class="max-w-md mx-auto">
                    <!-- Mobile Logo -->
                    <div class="block lg:hidden text-center">
                        <img width="120" src="{{ asset('assets/textio.png') }}" alt="logo" class="mx-auto">
                    </div>
                    <!-- Form Header -->
                    <div class="my-8 text-center lg:text-left">
                        <h1 class="text-2xl font-bold">Create Account</h1>
                        <p class="text-gray-500">You can create a free account now</p>
                    </div>
                    <!-- Form -->
                    <form wire:submit.prevent="register" class="mb-8">
                        <div class="mb-4">
                            <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter fullname" autofocus required>
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter email" required>
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <input type="password" wire:model="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter password" required>
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <input type="password" wire:model="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Re-enter password" required>
                        </div>
                        <div class="text-center lg:text-left">
                            <button type="submit" class="w-full lg:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Sign Up</button>
                        </div>
                    </form>
                   
                    <!-- Mobile Sign In Link -->
                    <p class="text-center block lg:hidden mt-8 text-gray-600">
                        Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign In</a>.
                    </p>
                </div>
            </div>
            <!-- Right Column: Welcome (Hidden on Mobile) -->
            <div class="hidden lg:flex w-1/2 border-l flex-col items-center justify-between p-6 text-center bg-gray-50">
                <div class="mt-8">
                    <img width="120" src="{{ asset('assets/textio.png') }}" alt="logo">
                </div>
                <div class="my-8">
                    <h3 class="text-xl font-bold">Welcome to Textio!</h3>
                    <p class="text-gray-600 my-6 max-w-md">Do you already have an account?</p>
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Sign In</a>
                </div>
                <ul class="flex space-x-4 mb-4">
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>