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
                        <h1 class="text-2xl font-bold">Sign In</h1>
                        <p class="text-gray-500">Sign in to Textio to continue</p>
                    </div>
                    <!-- Form -->
                    <form wire:submit.prevent="login" class="mb-8">
                        <div class="mb-4">
                            <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter email" autofocus required>
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <input type="password" wire:model="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter password" required>
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center mb-4">
                            <input type="checkbox" wire:model="remember" id="remember" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>
                        <div class="text-center lg:text-left">                              
                            <button type="submit" class="w-full lg:w-auto px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">Sign In</button>
                        </div>
                    </form>
                    <!-- Social Links -->
                    {{-- <div class="flex justify-center">
                        <a href="#" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            <i class="ti-google mr-2"></i> Sign in with Google
                        </a>
                    </div> --}}
                    <!-- Mobile Sign Up Link -->
                    <p class="text-center block lg:hidden mt-8 text-gray-600">
                        Don't have an account? <a href="{{ route('register') }}" class="text-purple-600 hover:underline">Sign up</a>.
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
                    <p class="text-gray-600 my-6 max-w-md">If you don't have an account, would you like to register right now?</p>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">Sign Up</a>
                </div>
                <ul class="flex space-x-4 mb-4">
                    <li><a href="#" class="text-gray-600 hover:text-purple-600">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-purple-600">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>