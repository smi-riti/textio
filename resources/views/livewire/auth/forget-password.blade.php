<div class="min-h-screen pt-24 flex items-center justify-center bg-gradient-to-br from-purple-50 via-white to-purple-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Forgot Password</h2>
                <p class="text-gray-600">Enter your email to reset your password</p>
            </div>

            @if (session()->has('success'))
                <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200">
                    <p class="text-sm text-green-600">{{ session('success') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200">
                    <p class="text-sm text-red-600">{{ session('error') }}</p>
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input type="email"
                           wire:model="email"
                           id="email"
                           class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                           placeholder="Enter your email">
                    @error('email') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                    
                    <span wire:loading.remove wire:target="sendResetLink">Send Reset Link</span>

                    <span wire:loading wire:target="sendResetLink" class="flex items-center">
                        Sending...
                    </span>
                    
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Remember your password?
                    <a href="{{route('auth.login')}}" class="font-medium text-purple-600 hover:text-purple-500">
                        Back to login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
